<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\CustomPage;
use App\Form\ProductFormType;
use App\Form\CustomPageType;
use App\Form\UpdateProductCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\CustomPageRepository;
use App\Entity\OrderedItems;
use App\Repository\OrderedItemsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserFormType;
use App\Form\ProductCategoryType;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin/", name="admin")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index()
    {

        return $this->render('admin/base.html.twig', [
            'title' => 'Admin panel',

        ]);
    }

    /**
     * @Route ("/admin/userEdit/{id}", name ="admin_user_edit")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     */

    public function editUser(Request $request,EntityManagerInterface $entityManager, User $user){

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully edited!');
            return $this->redirectToRoute('admin_users');
        }

            return $this->render('admin/edituser.html.twig', [
                'title' => 'Edit list',
                'user' => $user,
                'form' => $form->createView(),
            ]);

    }
    /**
     * @Route ("/admin/userOrder/{id}", name ="admin_user_order")
     * @return Response
     *
     */

    public function showUserOrder($id){

        $em = $this->getDoctrine()->getManager();
        $sql = "select s.id,  p.name, p.price, s.productnumber
                from shopcard s
                left join product p on s.product_id = p.id
                where s.product_id = p.id and s.user_id = :userid";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid',$id);
        $statement->execute();
        $shopcard = $statement->fetchAll();
        $total = 0;

        $sql = "select * from ordered_items where user_id = :userid";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid',$id);
        $statement->execute();
        $orderedItems = $statement->fetchAll();



        return $this->render('admin/orders.html.twig', [
            'title' => 'Orders',
            'shopcards' => $shopcard,
            'total' => $total,
            'orderedItems' => $orderedItems,

        ]);

    }
    /**
     * @Route ("/admin/updateOrder/{id}", name ="admin_update_order")
     * @return Response
     *
     */

    public function updateOrder($id,OrderedItemsRepository $orderedItemsRepository, EntityManagerInterface $entityManager){

        $order = $orderedItemsRepository->find($id);
        $order->setPaid('shipping');
        $entityManager->merge($order);
        $entityManager->flush();
        return $this->redirectToRoute('admin_users');

    }

    /**
     * @Route ("/admin/categoryProducts/{id}", name ="admin_category_products")
     * @return Response
     *
     */

    public function categoryProducts($id){

        $em = $this->getDoctrine()->getManager();
        $sql = "select a.name, a.image, a.name, a.price, a.id
                from product a
                inner join product_category pc on a.id = pc.product_id
                where category_id = :id";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $products = $statement->fetchAll();



        return $this->render('admin/categoryProducts.html.twig', [
            'products' => $products,
        ]);

    }

    /**
     * @Route ("/admin/customPages", name ="admin_custom_pages")
     * @param $customPageRepository CustomPageRepository
     * @return Response
     *
     */

    public function customPages(CustomPageRepository $customPageRepository ){



        return $this->render('admin/customPages.html.twig', [
            'customPages' => $customPageRepository->findAll()
        ]);
    }
    /**
     * @Route ("/admin/customPages/new", name ="customPage_new")
     * @param $customPageRepository CustomPageRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     *
     */

    public function customPagesNew(Request $request, EntityManagerInterface $entityManager, CustomPageRepository $customPageRepository){

        $form = $this->createForm(CustomPageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CustomPage $customPage */
            $customPage= $form->getData();
            $entityManager->persist($customPage);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new custom page!');
            return $this->redirectToRoute('admin_custom_pages');
        }



        return $this->render('admin/customPagesNew.html.twig', [
            'form' => $form->createView(),
        ]);
    }







}
