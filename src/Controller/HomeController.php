<?php

namespace App\Controller;
use App\Entity\Category;
use App\Entity\Shopcard;
use App\Entity\User;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\ShopcardRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Psr\Container\ContainerInterface;
use Knp\Component\Pager\PaginatorInterface;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param CategoryRepository $CategoryRepository
     * @param ProductRepository $ProductRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(Request $request,
                          PaginatorInterface $paginator,CategoryRepository $CategoryRepository ,ProductRepository $ProductRepository)
    {

        $categories = $CategoryRepository->findAll();
        $products = $ProductRepository->findAll();

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );



        return $this->render('home/index.html.twig', [
            'title' => 'Sport webshop',
            'categories' => $categories,
            'pagination' => $pagination

        ]);
    }
    /**
     * @Route("/product_details/{id}", name="product_details", methods={"GET"})
     * @param ProductRepository $ProductRepository
     */
    public function show($id,Product $product,ProductRepository $ProductRepository): Response
    {
        $product = $ProductRepository->find($id);

        return $this->render('home/productdetails.html.twig', [
            'product' => $product,
            'title' => 'Product details'
        ]);
    }

    /**
     * @Route("/category/{id}", name="category_details", methods={"GET"})
     * @param CategoryRepository $CategoryRepository
     *
     */
    public function showCategory(CategoryRepository $CategoryRepository, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "select * from product where category_id = :id";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $products = $statement->fetchAll();

        $categories = $CategoryRepository->findAll();

        return $this->render('home/categorydetails.html.twig', [
            'products' => $products,
            'title' => 'category details',
            'categories' => $categories

        ]);
    }
}
