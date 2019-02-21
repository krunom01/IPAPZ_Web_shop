<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Repository\ProductRepository;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CategoryRepository $CategoryRepository
     * @param ProductRepository $ProductRepository
     * @return Response
     */
    public function index(CategoryRepository $CategoryRepository, ProductRepository $ProductRepository)
    {
        $categories = $CategoryRepository->findAll();
        $products = $ProductRepository->findAll();

        return $this->render('home/index.html.twig', [
            'title' => 'Sport webshop',
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    /**
     * @Route("/product_details/{id}", name="product_details", methods={"GET"})
     * @param ProductRepository $ProductRepository
     */
    public function show(Product $product): Response
    {
        return $this->render('home/productdetails.html.twig', [
            'product' => $product,
            'title' => 'Product details'
        ]);
    }
}
