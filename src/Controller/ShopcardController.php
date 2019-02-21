<?php

namespace App\Controller;

use App\Entity\Shopcard;
use App\Entity\User;
use App\Form\ShopcardType;
use App\Repository\ShopcardRepository;
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
        echo $user->getId();
        $em = $this->getDoctrine()->getManager();
        $sql = "select p.name, p.price, s.id
        from shopcard s, product p, user a
        where s.product_id = p.id and a.id = :userid";

        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('userid', $user->getId());
        $statement->execute();
        $shopcard = $statement->fetchAll();
        return $this->render('shopcard/index.html.twig', [
            'shopcards' =>  $shopcard,
        ]);
    }

    /**
     * @Route("/new", name="shopcard_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $shopcard = new Shopcard();
        $form = $this->createForm(ShopcardType::class, $shopcard);
        $form->handleRequest($request);



            $entityManager = $this->getDoctrine()->getManager();
            $user = $this-> getUser();
            $shopcard->setUserid($user->getId());
            $shopcard->setProduct($request->request->get('productid'));
            $entityManager->persist($shopcard);
            $entityManager->flush();

            return $this->redirectToRoute('shopcard_index');


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
