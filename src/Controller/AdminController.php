<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
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

}
