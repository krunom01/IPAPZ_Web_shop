<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\OrderedItems;
use App\Repository\OrderedItemsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserFormType;


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
     * @param EntityManagerInterface $entityManage
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

    public function shoUserOrder($id){

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

}
