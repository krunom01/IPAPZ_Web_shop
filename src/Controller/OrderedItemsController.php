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
            $orderedItem->setPaid('new');
            $entityManager->persist($orderedItem);

            $entityManager->flush();
            $this->addFlash('success', 'OK!');
            return $this->redirectToRoute('home');
        }

        return $this->render('ordered_items/new.html.twig', [
            'ordered_item' => $orderedItem,
            'form' => $form->createView(),
            'message' => '',
        ]);
    }

    /**
     * @Route("/admin/allOrders", name="order_index", methods={"GET"})
     */
    public function orders(OrderedItemsRepository $orderedItemsRepository): Response
    {
        $orders = $orderedItemsRepository->findAll();

        return $this->render('admin/allOrders.html.twig', [
            'orders' => $orders,

        ]);
    }




}
