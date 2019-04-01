<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Wishlist;
use App\Form\WishListType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class WishlistController extends AbstractController
{
    /**
     * @Symfony\Component\Routing\Annotation\Route("/shopcard/newWishlist/{id}", name="wishlist_new")
     * @param                               EntityManagerInterface $entityManager
     * @param                               Request $request
     * @param Product $product
     * @return          \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Product $product)
    {

        $wishList = new Wishlist();
        $form = $this->createForm(WishListType::class, $wishList);
        $form->handleRequest($request);
        $user = $this->getUser();
        $wishList->setUser($user);
        $wishList->setProduct($product);
        $entityManager->persist($wishList);
        $entityManager->flush();

        $form->handleRequest($request);
        $this->addFlash('success', 'Successfully added new item in your Wishlist !');
        return $this->redirectToRoute('home');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/shopcard/removeWishlist/{id}", name="wishlist_remove")
     * @param                                  EntityManagerInterface $entityManager
     * @param                                  Request $request
     * @param                                  Wishlist $wishlist
     * @return         \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function remove(Request $request, EntityManagerInterface $entityManager, Wishlist $wishlist)
    {
        if ($this->isCsrfTokenValid('delete' . $wishlist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($wishlist);
            $entityManager->flush();
        } $this->addFlash('success', 'You deleted item from wishlist!');
        return $this->redirectToRoute('home');
    }
}
