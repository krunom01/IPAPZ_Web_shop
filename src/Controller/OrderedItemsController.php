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
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/")
 */
class OrderedItemsController extends AbstractController
{
    /**
     * @Route("/admin/orders", name="ordered_items_index", methods={"GET"})
     * @param OrderedItemsRepository $orderedItemsRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request,OrderedItemsRepository $orderedItemsRepository,PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('OrderedItemsRepository')->findAll();

        $pagination = $paginator->paginate(
            $orders, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            1/*limit per page*/
        );

        return $this->render('admin/orders.html.twig', [
            'orders' => $pagination,

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

            $entityManager = $this->getDoctrine()->getManager();
            $orderedItem->setUser($this->getUser());
            $orderedItem->setUserEmail($this->getUser()->getEmail());
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
     * @param OrderedItemsRepository $orderedItemsRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     *
     */
    public function orders(Request $request,OrderedItemsRepository $orderedItemsRepository,PaginatorInterface $paginator)
    {


        $orders = $orderedItemsRepository->createQueryBuilder('user');

        if($request->query->getAlnum('email')) {

            $orders->where('user.userEmail = :userEmail')
                ->setParameter('userEmail', $request->query->getAlnum('email'));
        };
        $pagination = $paginator->paginate(
            $orders, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        return $this->render('admin/allOrders.html.twig', [
            'orders' => $pagination,

        ]);
    }




}
