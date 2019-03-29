<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderedItems;
use App\Form\CartFormType;
use App\Form\InsertCouponType;
use App\Form\CartItemType;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\CountryShippingRepository;
use App\Repository\CouponRepository;
use App\Repository\CustomPageRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\ProductCategoryRepository;
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
     * @param      CustomPageRepository $customPageRepository
     * @param      PaginatorInterface $paginator
     * @param      Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository,
        CustomPageRepository $customPageRepository,
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
            'home/home.html.twig',
            [
                'title' => 'Sport webshop',
                'categories' => $categories,
                'customPages' => $customPageRepository->findAll(),
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
                'user' => $user,
            ]
        );

        $cartItem = new CartItem();
        $form = $this->createForm(CartItemType::class, $cartItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
            // find user in cart
            $cart = new Cart();
            if (!$userCart) {
                $cart->setUser($user);
                $cart->setCoupon(0);
                $entityManager->persist($cart);
                $entityManager->flush();
            }   $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
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
     * @param $id
     * @param ProductCategoryRepository $productCategoryRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function showCategory(
        CategoryRepository $categoryRepository,
        $id,
        ProductCategoryRepository $productCategoryRepository
    ) {

        $categories = $categoryRepository->findAll();
        $categoryProducts = $productCategoryRepository->findBy(['category' => $id]);

        return $this->render(
            'home/categorydetails.html.twig',
            [
                'title' => 'category details',
                'categories' => $categories,
                'categoryProducts' => $categoryProducts

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
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);

        if (!$userCart) {
            $shopCartTotal = 0;
            $items = 0;
            $coupon = 0;
        } else {
            $shopCartTotal = $userCart->getSubTotal();
            $items = $userCart->getCartItems();
            $coupon = $userCart->getCoupon();
        }

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
                $this->addFlash('success', '' . $coupon->getDiscount() . '% discount on total price');
                return $this->redirectToRoute('shopCart');
            }
        } return $this->render(
            'home/shopcart.html.twig',
            [
                'items' => $items,
                'total' => $shopCartTotal,
                'form' => $form->createView(),
                'coupon' => $coupon,

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
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
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
     * @param CartItemRepository $cartItemRepository
     * @param EntityManagerInterface $entityManager
     * @param CountryShippingRepository $countryShippingRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function userNewOrder(
        Request $request,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        CouponRepository $couponRepository,
        EntityManagerInterface $entityManager,
        CountryShippingRepository $countryShippingRepository
    ) {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
        if ($userCart == null) {
            $this->addFlash('success', 'please add some products before order');
            return $this->redirectToRoute('home');
        } $userCart->getSubTotal();
        $form = $this->createForm(InsertCouponType::class);

        $form->handleRequest($request);
        $formCart = $this->createForm(CartFormType::class);
        $formCart->handleRequest($request);

        if ($formCart->isSubmitted() && $formCart->isValid()) {
            $price =  $formCart->get('country')->getData()->getShippingPrice();
            if ($formCart->get('address')->getData() != null) {
                $userCart->setTotal($userCart->getSubTotal() + $price);
                $userCart->setCountry($formCart->get('country')->getData());
                $userCart->setAddress($formCart->get('address')->getData());

                $entityManager->persist($userCart);
                $entityManager->flush();
                $this->addFlash('success', 'Pick your payment method');
                return $this->redirectToRoute('confirmOrder');
            }
        } return $this->render(
            'home/newOrder.html.twig',
            [
                'items' => $userCart->getCartItems(),
                'total' => $userCart->getSubTotal(),
                'form' => $form->createView(),
                'coupon' => $userCart->getCoupon(),
                'formCart' => $formCart->createView(),
            ]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/confirmOrder", name="confirmOrder")
     * @param CartRepository $cartRepository
     * @param Request $request
     * @param CouponRepository $couponRepository
     * @param PaymentTypeRepository $paymentType
     * @param EntityManagerInterface $entityManager
     * @param CountryShippingRepository $countryShippingRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function userOrderConfirm(
        Request $request,
        CartRepository $cartRepository,
        CouponRepository $couponRepository,
        EntityManagerInterface $entityManager,
        CountryShippingRepository $countryShippingRepository,
        PaymentTypeRepository $paymentType
    ) {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
        if ($userCart == null) {
            $this->addFlash('success', 'Add products in shopcart and set your address');
            return $this->redirectToRoute('home');
        }
        if (empty($userCart->getSubTotal()) or empty($userCart->getAddress())) {
            $this->addFlash('success', 'Add products in shopcart and set your address');
            return $this->redirectToRoute('home');
        } $form = $this->createForm(InsertCouponType::class);
        $form->handleRequest($request);
        $gateway = self::gateway();
        $shippingPrice = $countryShippingRepository->findOneBy(['country' => $userCart->getCountry()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $couponRepository->findOneBy(['code' => $form->get('code')->getData()]);
            if (!$coupon) {
                $this->addFlash('success', 'Wrong coupon code!');
                return $this->redirectToRoute('confirmOrder');
            } else {
                $total = $userCart->getTotal();
                $discount = 1 - ('0.' . $coupon->getDiscount());
                $priceWithDiscount = $total * $discount;
                $userCart->setTotal($priceWithDiscount);
                $userCart->setCoupon(1);
                $entityManager->persist($userCart);
                $entityManager->flush();
                $this->addFlash('success', 'You get ' . $coupon->getDiscount() . '% discount on total price');
                return $this->redirectToRoute('confirmOrder');
            }
        }  return $this->render(
            'home/confirmOrder.html.twig',
            [
                'items' => $userCart->getCartItems(),
                'total' => $userCart->getSubTotal(),
                'totalWithShipping' => $userCart->getTotal(),
                'shippingPrice' => $shippingPrice->getShippingPrice(),
                'form' => $form->createView(),
                'coupon' => $userCart->getCoupon(),
                'payments' => $paymentType->findBy(['visibility' => '1']),
                'gateway' => $gateway,
                'usercart' => $userCart
            ]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/invoiceOrder", name="invoiceOrder")
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function invoiceOrder(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        EntityManagerInterface $entityManager
    ) {
        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);

        $order = new Order();
        $order->setUserName($user->getFullName());
        $order->setState($userCart->getCountry());
        $order->setStatus('new');
        $order->setUserMail($user->getEmail());
        $order->setType('invoice');
        $order->setTotalPrice($userCart->getTotal());
        $order->setUserId($user);
        $order->setAddress($userCart->getAddress());
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
            } $entityManager->remove($userCart);
            $entityManager->flush();
            $this->addFlash('success', 'You made new Order!');
            return $this->redirectToRoute('home');
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/cms/{customPage}/", name="customPage", methods={"GET"})
     * @param CustomPageRepository $customPageRepository
     * @param $customPage
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function showCustomPage(
        CustomPageRepository $customPageRepository,
        $customPage
    ) {

        $page = $customPageRepository->findOneBy(['customUrl' => $customPage]);

        return $this->render(
            'home/customPage.html.twig',
            [
                'page' => $page
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/paypal", name="paypal_payment")
     * @param Request $request
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function payment(
        EntityManagerInterface $entityManager,
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        Request $request

    ) {


        $user = $this->getUser();
        $userCart = $cartRepository->findOneBy(['user' => $user->getId()]);
        if ($userCart == null) {
            $this->addFlash('success', 'Add products in shopcart and set your address');
            return $this->redirectToRoute('home');
        }

        $order = new Order();
        $order->setUserName($user->getFullName());
        $order->setState($userCart->getCountry());
        $order->setStatus('paid');
        $order->setUserMail($user->getEmail());
        $order->setType('paypal');
        $order->setTotalPrice($userCart->getTotal());
        $order->setUserId($user);
        $order->setAddress($userCart->getAddress());
        $entityManager->persist($order);
        $entityManager->flush();
        $userCartItems = $cartItemRepository->findOneBy(['userId' => $user->getId()]);
        $gateway = self::gateway();

        $nonce = $request->get('payment_method_nonce');
        $result = $gateway->transaction()->sale(
            [
                'amount' => $userCart->getTotal(),
                'paymentMethodNonce' => $nonce
            ]
        );
        $transaction = $result->transaction;
        if ($transaction == null) {
            $this->addFlash('warning', 'Payment unsuccessful!');
            return $this->redirectToRoute('confirmOrder');
        } $cartItems = $userCart->getCartItems();
        foreach ($cartItems as $cartItem) {
            $itemsOrder = new OrderedItems();
            $itemsOrder->setUserId($user->getId());
            $itemsOrder->setProductPrice($userCartItems->getProductPrice());
            $itemsOrder->setProductQuantity($userCartItems->getProductQuantity());
            $itemsOrder->setProduct($userCartItems->getProduct());
            $itemsOrder->setOrder($order);
            $entityManager->persist($itemsOrder);
            $entityManager->flush();
        } $entityManager->remove($userCart);
        $entityManager->flush();
        $this->addFlash('success', 'You made new Order!');

        return $this->redirectToRoute('home');
    }

    private function gateway()
    {
        return $gateway = new \Braintree_Gateway(
            [
                'environment' => 'sandbox',
                'merchantId' => '3z58zqv68q7ktm5q',
                'publicKey' => 'ttrdtktqbbqvn3wg',
                'privateKey' => '595fa94c954f64725283892411cd9ddf'
            ]
        );
    }
}
