<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\CartItemType;
use App\Entity\Wishlist;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\WishlistRepository;
use App\Repository\UserRepository;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Psr\Container\ContainerInterface;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param      CategoryRepository $CategoryRepository
     * @param      ProductRepository $ProductRepository
     * @param      PaginatorInterface $paginator
     * @param      Request $request
     * @return     Response
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        CategoryRepository $CategoryRepository,
        ProductRepository $ProductRepository
    ) {
        $categories = $CategoryRepository->findAll();
        $products = $ProductRepository->findAll();
        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render(
            'home/index.html.twig',
            [
                'title' => 'Sport webshop',
                'categories' => $categories,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/product_details/{id}/{urlCustom}", name="product_details")
     * @param                                      ProductRepository $ProductRepository
     * @param                                      WishlistRepository $WishListRepository
     * @param                                      CartRepository $cartRepository
     * @param                                      CartItemRepository $cartItemRepository
     * @param                                      $id
     * @param                                      Request $request
     * @param                                      EntityManagerInterface $entityManager
     * @return                                     Response
     */
    public function showProduct(
        $id,
        ProductRepository $ProductRepository,
        WishlistRepository $WishListRepository,
        CartRepository $cartRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        CartItemRepository $cartItemRepository
    ) {

        $product = $ProductRepository->find($id);
        $user = $this->getUser();
        $wishListProduct = $WishListRepository->findOneBy(
            [
                'product' => $product,
                'user' => $user->getId()
            ]
        );

        $cartItem = new CartItem();
        $form = $this->createForm(CartItemType::class, $cartItem);
        $form->handleRequest($request);
        $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
        if ($form->isSubmitted() && $form->isValid()) {
            // find user in cart
            $cart = new Cart();
            if (!$userCart) {
                $cart->setUserId($user->getId());
                $entityManager->persist($cart);
                $entityManager->flush();
            }

            $cartItem->setProductId($product->getId());
            $cartItem->setProductPrice($product->getPrice());
            $cartItem->setCart($userCart);
            $entityManager->persist($cartItem);
            $entityManager->flush();

            $totalCartMoney = 0;

            $userCardTotals = $cartItemRepository->findUserCart($user->getId());

            foreach ($userCardTotals as $userCardTotal) {
                $totalCartMoney += $userCardTotal->getProductPrice() * $userCardTotal->getProductQuantity();
            }

            $userCart->setSubTotal($totalCartMoney);
            $entityManager->persist($userCart);
            $entityManager->flush();
        }

        return $this->render(
            'home/productdetails.html.twig',
            [
                'product' => $product,
                'title' => 'Product details',
                'wishlistProduct' => $wishListProduct,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/category/{id}", name="category_details", methods={"GET"})
     * @param                   CategoryRepository $CategoryRepository
     * @return                  Response
     */
    public function showCategory(CategoryRepository $CategoryRepository, $id)
    {
        $categories = $CategoryRepository->findAll();
        $em = $this->getDoctrine()->getManager();
        $sql = "select a.name, a.image, a.name, a.price, a.id
                from product a
                inner join product_category pc on a.id = pc.product_id
                where category_id = :id";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $products = $statement->fetchAll();


        return $this->render(
            'home/categorydetails.html.twig',
            [
                'products' => $products,
                'title' => 'category details',
                'categories' => $categories

            ]
        );
    }

    /**
     * @Route("/category/{id}", name="new_cart", methods={"GET"})
     * @param $id
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function newUserCart($id, ProductRepository $productRepository)
    {

        $product = $productRepository->findBy($id);

        return $this->render(
            'home/categorydetails.html.twig',
            [


            ]
        );
    }


    /**
     * @Route("/shopcart", name="shopCart", methods={"GET"})
     * @param CartRepository $cartRepository
     * @return Response
     */
    public function usershopCart(CartRepository $cartRepository)
    {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);

        $nesto = $userCart->getCartItems();

        return $this->render(
            'home/shopcart.html.twig',
            [
                'items' => $userCart->getCartItems(),

            ]
        );
    }
}
