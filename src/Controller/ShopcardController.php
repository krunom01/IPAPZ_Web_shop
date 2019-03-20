<?php

namespace App\Controller;

use App\Entity\Shopcard;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Wishlist;
use App\Entity\Coupon;
use App\Entity\OrderedItems;
use App\Form\ShopcardType;
use App\Form\WishListType;
use App\Form\InsertCouponType;
use App\Repository\ShopcardRepository;
use App\Repository\ProductRepository;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManager;
use App\Form\OrderedItemsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/shopcard")
 */
class ShopcardController extends AbstractController
{
    /**
     * @Route("/", name="shopcard_index")
     * @param Request $request
     * @param CouponRepository $couponRepository
     * @return Response
     */
    public function index(Request $request, CouponRepository $couponRepository)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $sql = "select s.id,  p.name, p.price, s.productnumber, s.product_id
                from shopcard s
                left join product p on s.product_id = p.id
                where s.product_id = p.id and s.user_id = :userid";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid', $user->getId());
        $statement->execute();
        $shopcards = $statement->fetchAll();


        $total = 0;
        foreach ($shopcards as $shopcard){
            $total = $total + ($shopcard['productnumber'] * $shopcard['price']);
        }


        $orderedItem = new OrderedItems();
        $form = $this->createForm(OrderedItemsType::class, $orderedItem);
        $form->handleRequest($request);

        $formCoupon = $this->createForm(InsertCouponType::class);
        $formCoupon->handleRequest($request);

        if ($formCoupon->isSubmitted() && $formCoupon->isValid()) {
            $code = $formCoupon->getData();

            $coupon = $couponRepository->findOneBy(['code' => $code]);

            if(!$coupon){
                $this->addFlash('warning', 'wrong coupon code');
                return $this->redirectToRoute('shopcard_index');
            } else {

                $discount =  "0.".  $coupon->getDiscount();
                $total = $total * $discount ;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $orderedItem->setUser($this->getUser());
            $orderedItem->setUserEmail($this->getUser()->getEmail());
            $orderedItem->setPaid('new');
            $orderedItem->setTotalPrice($total);
            $entityManager->persist($orderedItem);
            $entityManager->flush();
            $this->addFlash('success', 'OK!');
            return $this->redirectToRoute('home');
        }


        return $this->render('shopcard/index.html.twig', [
            'shopcards' =>  $shopcards,
            'title' => "shopcard details",
            'total' => $total,
            'form' => $form->createView(),
            'formCoupon' => $formCoupon->createView()
        ]);

    }

    /**
     * @Route("/new/{id}", name="shopcard_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Product $product)
    {

        $shopcard = new Shopcard();
        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);
            $user = $this->getUser();
            $shopcard->setUser($user);
            $shopcard->setProduct($product);
            $shopcard->setProductnumber($request->request->get('quantity'));
            $entityManager->persist($shopcard);
            $entityManager->flush();

        $form->handleRequest($request);
        $this->addFlash('success', 'go to shopcart to order!');
            return $this->redirectToRoute('home');



    }


    /**
     * @Route("/{id}/delete", name="shopcard_delete")
     */
    public function delete(Request $request, Shopcard $shopcard): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shopcard);
            $entityManager->flush();


        return $this->redirectToRoute('shopcard_index');
    }
}
