<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderedItems;
use App\Form\InsertCouponType;
use App\Form\CartItemType;
use App\Form\OrderFormType;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\CouponRepository;
use App\Repository\WishlistRepository;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Symfony\Component\Routing\Annotation\Route("/", name="home")
     * @param      CategoryRepository $categoryRepository
     * @param      ProductRepository $productRepository
     * @param      PaginatorInterface $paginator
     * @param      Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
    ) {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();
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
     * @Symfony\Component\Routing\Annotation\Route("/product_details/{id}/{urlCustom}", name="product_details")
     * @param                                      ProductRepository $productRepository
     * @param                                      WishlistRepository $wishListRepository
     * @param                                      CartRepository $cartRepository
     * @param                                      CartItemRepository $cartItemRepository
     * @param                                      $id
     * @param                                      Request $request
     * @param                                      EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function showProduct(
        $id,
        ProductRepository $productRepository,
        WishlistRepository $wishListRepository,
        CartRepository $cartRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        CartItemRepository $cartItemRepository
    ) {

        $product = $productRepository->find($id);
        $user = $this->getUser();
        $wishListProduct = $wishListRepository->findOneBy(
            [
                'product' => $product,
                'user' => $user->getId()
            ]
        );

        $cartItem = new CartItem();
        $form = $this->createForm(CartItemType::class, $cartItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
            // find user in cart
            $cart = new Cart();
            if (!$userCart) {
                $cart->setUserId($user->getId());
                $cart->setCoupon(0);
                $entityManager->persist($cart);
                $entityManager->flush();
            }   $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
                $cartItem->setUserId($user->getId());
                $cartItem->setProduct($product);
                $cartItem->setProductPrice($product->getPrice());
                $cartItem->setCart($userCart);
                $entityManager->persist($cartItem);
                $entityManager->flush();

            $totalCartMoney = 0;

            $userCardTotals = $cartItemRepository->findUserCart($user->getId());


            foreach ($userCardTotals as $userCardTotal) {
                $totalCartMoney += $userCardTotal->getProductPrice() * $userCardTotal->getProductQuantity();
            }

            if ($totalCartMoney == 0) {
                $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
                $totalCartMoney = $product->getPrice() * $form->get('productQuantity')->getData();
            }

            $userCart->setSubTotal($totalCartMoney);
            $entityManager->persist($userCart);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new item in cart !');
            return $this->redirectToRoute('home');
        }


        return $this->render(
            'home/productdetails.html.twig',
            [
                'product' => $product,
                'title' => 'Product details',
                'wishlistProduct' => $wishListProduct,
                'form' => $form->createView(),

            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/category/{id}", name="category_details", methods={"GET"})
     * @param                   CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function showCategory(CategoryRepository $categoryRepository, $id)
    {
        $categories = $categoryRepository->findAll();
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
     * @Symfony\Component\Routing\Annotation\Route("/category/{id}", name="new_cart", methods={"GET"})
     * @param $id
     * @param ProductRepository $productRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
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
     * @Symfony\Component\Routing\Annotation\Route("/shopcart", name="shopCart")
     * @param CartRepository $cartRepository
     * @param Request $request
     * @param CouponRepository $couponRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function userShopCart(
        Request $request,
        CartRepository $cartRepository,
        CouponRepository $couponRepository,
        EntityManagerInterface $entityManager
    ) {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
        $userCart->getSubTotal();
        $form = $this->createForm(InsertCouponType::class);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $couponRepository->findOneBy(['code' => $form->get('code')->getData()]);
            if (!$coupon) {
                $this->addFlash('success', 'Wrong coupon code!');
                return $this->redirectToRoute('shopCart');
            } else {
                $total = $userCart->getSubTotal();
                $discount = 1 - ('0.' . $coupon->getDiscount());
                $priceWithDiscount = $total * $discount;
                $userCart->setSubTotal($priceWithDiscount);
                $userCart->setCoupon(1);
                $entityManager->persist($userCart);
                $entityManager->flush();
                $this->addFlash('success', 'You get ' . $coupon->getDiscount() . '% discount on total price');
                return $this->redirectToRoute('shopCart');
            }
        } return $this->render(
            'home/shopcart.html.twig',
            [
                'items' => $userCart->getCartItems(),
                'total' => $userCart->getSubTotal(),
                'form' => $form->createView(),
                'coupon' => $userCart->getCoupon()

            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/shopcart/delete/{id}", name="shopCartDeleteIdem")
     * @param CartItemRepository $cartItemRepository
     * @param CartRepository $cartRepository
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function deleteItemFromShopCart(
        CartItemRepository $cartItemRepository,
        CartRepository $cartRepository,
        $id
    ) {

        $user = $this->getUser();
        $userCartItem = $cartItemRepository->findOneBy(['userId' => $user->getId(), 'product' => $id]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($userCartItem);
        $entityManager->flush();

        $totalCartMoney = 0;
        $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);
        $userCardTotals = $cartItemRepository->findUserCart($user->getId());

        foreach ($userCardTotals as $userCardTotal) {
            $totalCartMoney += $userCardTotal->getProductPrice() * $userCardTotal->getProductQuantity();
        } $userCart->setSubTotal($totalCartMoney);
        $entityManager->persist($userCart);
        $entityManager->flush();

        return $this->redirectToRoute('shopCart');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/neworder", name="newOrder")
     * @param CartRepository $cartRepository
     * @param Request $request
     * @param CouponRepository $couponRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function userNewOrder(
        Request $request,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        CouponRepository $couponRepository,
        EntityManagerInterface $entityManager
    ) {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['userId' => $user->getId()]);

        $userCart->getSubTotal();
        $form = $this->createForm(InsertCouponType::class);
        $form->handleRequest($request);
        $formOrder = $this->createForm(OrderFormType::class);
        $formOrder->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $couponRepository->findOneBy(['code' => $form->get('code')->getData()]);
            if (!$coupon) {
                $this->addFlash('success', 'Wrong coupon code!');
                return $this->redirectToRoute('newOrder');
            } else {
                $total = $userCart->getSubTotal();
                $discount = 1 - ('0.' . $coupon->getDiscount());
                $priceWithDiscount = $total * $discount;
                $userCart->setSubTotal($priceWithDiscount);
                $userCart->setCoupon(1);
                $entityManager->persist($userCart);
                $entityManager->flush();
                $this->addFlash('success', 'You get ' . $coupon->getDiscount() . '% discount on total price');
                return $this->redirectToRoute('newOrder');
            }
        } if ($formOrder->isSubmitted() && $formOrder->isValid()) {
            $order = new Order();
            $order->setTotalPrice($userCart->getSubTotal());
            $order->setState($formOrder->get('state')->getData());
            $order->setType($formOrder->get('type')->getData());
            $order->setAddress($formOrder->get('address')->getData());
            $order->setUserMail($user->getEmail());
            $order->setUserName($user->getFirstName());
            $order->setUserId($user->getId());
            $entityManager->persist($order);
            $entityManager->flush();

            $userCartItems = $cartItemRepository->findOneBy(['userId' => $user->getId()]);
            $cartItems = $userCart->getCartItems();
            foreach ($cartItems as $cartItem) {
                $itemsOrder = new OrderedItems();
                $itemsOrder->setUserId($user->getId());
                $itemsOrder->setProductPrice($userCartItems->getProductPrice());
                $itemsOrder->setProductQuantity($userCartItems->getProductQuantity());
                $itemsOrder->setProduct($userCartItems->getProduct());
                $itemsOrder->setOrder($order);
                $entityManager->persist($itemsOrder);
                $entityManager->flush();
            }
            $entityManager->remove($userCart);
            $entityManager->flush();
        } return $this->render(
            'home/newOrder.html.twig',
            [
                'items' => $userCart->getCartItems(),
                'total' => $userCart->getSubTotal(),
                'form' => $form->createView(),
                'coupon' => $userCart->getCoupon(),
                'formCoupon' => $formOrder->createView(),
            ]
        );
    }
}
