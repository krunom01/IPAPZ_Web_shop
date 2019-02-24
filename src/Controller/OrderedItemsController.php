<?php

namespace App\Controller;

use App\Entity\OrderedItems;
use App\Entity\User;
use App\Form\OrderedItemsType;
use App\Repository\OrderedItemsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class OrderedItemsController extends AbstractController
{
    /**
     * @Route("/admin/orders", name="ordered_items_index", methods={"GET"})
     */
    public function index(OrderedItemsRepository $orderedItemsRepository): Response
    {
        $orders = $orderedItemsRepository->findAll();

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,

        ]);
    }

    /**
     * @Route("/neworder", name="ordered_items_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $orderedItem = new OrderedItems();
        $form = $this->createForm(OrderedItemsType::class, $orderedItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $entityManager = $this->getDoctrine()->getManager();
            $orderedItem->setUser($user);
            $entityManager->persist($orderedItem);
            $entityManager->flush();
            $this->addFlash('success', 'Your order products!');
            return $this->redirectToRoute('home');
        }

        return $this->render('ordered_items/new.html.twig', [
            'ordered_item' => $orderedItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/orders/{id}", name="ordered_items_show", methods={"GET"})
     */
    public function show(OrderedItems $orderedItems): Response
    {

        $orderedItems->getItems();

        return $this->render('ordered_items/show.html.twig', [
            'ordereditems' => $orderedItems,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ordered_items_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrderedItems $orderedItem): Response
    {
        $form = $this->createForm(OrderedItemsType::class, $orderedItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ordered_items_index', [
                'id' => $orderedItem->getId(),
            ]);
        }

        return $this->render('ordered_items/edit.html.twig', [
            'ordered_item' => $orderedItem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ordered_items_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OrderedItems $orderedItem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderedItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($orderedItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ordered_items_index');
    }


}
