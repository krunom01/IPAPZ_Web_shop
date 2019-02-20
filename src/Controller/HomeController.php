<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CategoryRepository $CategoryRepository
     * @return Response
     */
    public function index(CategoryRepository $CategoryRepository)
    {
        $categories = $CategoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'title' => 'Sport webshop',
            'categories' => $categories,
        ]);
    }
}
