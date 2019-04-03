<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * * Class CategoryController
 *
 * @package                  App\Controller
 * @Symfony\Component\Routing\Annotation\Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Symfony\Component\Routing\Annotation\Route("/", name="category_index", methods={"GET"})
     * @param      CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $number = $categoryRepository->findAll();


        return $this->render(
            'category/index.html.twig',
            [
                'categories' => $categoryRepository->findAll(),
                'number' => $number,
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/new", name="category_new", methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/new.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="category_show", methods={"GET"})
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Category $category)
    {
        return $this->render(
            'category/show.html.twig',
            [
                'category' => $category,
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/edit/{id}", name="category_edit")
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'category_index',
                [
                    'id' => $category->getId(),
                ]
            );
        }

        return $this->render(
            'category/edit.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/{id}", name="category_delete", methods={"DELETE"})
     * @param Category $category
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Category $category)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
