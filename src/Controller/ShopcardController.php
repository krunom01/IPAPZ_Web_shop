<?php

namespace App\Controller;

use App\Entity\Shopcard;
use App\Entity\User;
use App\Entity\Product;
use App\Form\ShopcardType;
use App\Repository\ShopcardRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopcard")
 */
class ShopcardController extends AbstractController
{
    /**
     * @Route("/", name="shopcard_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $sql = "select s.id,  p.name, p.price, s.productnumber
                from shopcard s
                left join product p on s.product_id = p.id
                where s.product_id = p.id and s.user_id = :userid";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid', $user->getId());
        $statement->execute();
        $shopcard = $statement->fetchAll();
        $total = 0;
        return $this->render('shopcard/index.html.twig', [
            'shopcards' =>  $shopcard,
            'title' => "shopcard details",
            'total' => $total
        ]);
    }

    /**
     * @Route("/new/{id}", name="shopcard_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Product $product)
    {
        $shopcard = new Shopcard();
        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);
            $user = $this->getUser();
            $shopcard->setUser($user);
            $shopcard->setProduct($product);
            $shopcard->setProductnumber($request->request->get('quantity'));
            $entityManager->persist($shopcard);
            $entityManager->flush();
        $this->addFlash('success', 'go to shopcart to order!');
            return $this->redirectToRoute('home');


    }

    /**
     * @Route("/{id}/edit", name="shopcard_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Shopcard $shopcard): Response
    {

        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            return $this->redirectToRoute('shopcard_index', [
                'id' => $shopcard->getId(),
            ]);
        }

        return $this->render('shopcard/edit.html.twig', [
            'shopcard' => $shopcard,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="shopcard_delete")
     */
    public function delete(Request $request, Shopcard $shopcard): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shopcard);
            $entityManager->flush();


        return $this->redirectToRoute('shopcard_index');
    }
}
