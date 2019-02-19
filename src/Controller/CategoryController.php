<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="admin_categories")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'New category created!');
            return $this->redirectToRoute('admin_categories');
        }
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('admin/categorylist.html.twig', [
            'form' => $form->createView(),
            'title' => 'Category list',
            'categories' => $categories
        ]);

    }
    /**
     * @Route("/admin/category/delete/{id}", name="category_delete")
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser(Category $category, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted!');
        return $this->redirectToRoute('admin_categories');
    }
}
