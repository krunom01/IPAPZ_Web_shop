<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Wishlist;
use App\Form\WishListType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WishlistController extends AbstractController
{
    /**
     * @Route("/shopcard/newWishlist/{id}", name="wishlist_new")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Product $product)
    {

        $wishlist = new Wishlist();
        $form = $this->createForm(WishListType::class, $wishlist);
        $form->handleRequest($request);
        $user = $this->getUser();
        $wishlist->setUser($user);
        $wishlist->setProduct($product);
        $entityManager->persist($wishlist);
        $entityManager->flush();

        $form->handleRequest($request);
        $this->addFlash('success', 'ok');
        return $this->redirectToRoute('home');

    }
}
