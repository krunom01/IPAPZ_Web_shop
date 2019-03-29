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

        $wishlist = new Wishlist();
        $form = $this->createForm(WishListType::class, $wishlist);
        $form->handleRequest($request);
        $user = $this->getUser();
        $wishlist->setUser($user);
        $wishlist->setProduct($product);
        $entityManager->persist($wishlist);
        $entityManager->flush();

        $form->handleRequest($request);
        $this->addFlash('success', 'Successfully added new item in your wishlist !');
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
