<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends AbstractController
{

    /**
     * @Route("/admin/", name="admin")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param  $postRepository
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
     *
     */

    public function editUser($id){

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        return $this->render('admin/edituser.html.twig', [
            'title' => 'Edit list',
            'user' => $user,
        ]);
    }

}
