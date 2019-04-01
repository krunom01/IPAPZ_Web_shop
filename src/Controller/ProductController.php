<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\UpdateProductType;
use App\Repository\CategoryRepository;
use App\Form\ProductFormType;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Symfony\Component\Routing\Annotation\Route("admin/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Symfony\Component\Routing\Annotation\Route("/", name="product_index")
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "select distinct a.name, a.productnumber,a.id,a.image,a.price,a.url_custom, pc.product_id
                from product a
                left join product_category pc on a.id = pc.product_id";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $products = $statement->fetchAll();

        return $this->render(
            'admin/products.html.twig',
            [
                'products' => $products,

            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/new", name="product_new", methods={"GET","POST"})
     * @param         Request $request
     * @param         EntityManagerInterface $entityManager
     * @param         CategoryRepository $categoryRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function createProduct(
        Request $request,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ) {

        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Product $product
             */
            $product = $form->getData();
            $file = $product->getImage();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                echo $e;
            } $product->setImage($fileName);
            $spaceReplace = $product->getUrlCustom();
            $string = preg_replace('/\s+/', '-', $spaceReplace);
            $product->setUrlCustom($string . $product->getProductnumber());
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Inserted new product!');
            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new.html.twig',
            [
                'categories' => $categoryRepository->findAll(),
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Symfony\Component\Routing\Annotation\Route("/updateproduct/{id}", name="update_product")
     * @param                        EntityManagerInterface $entityManager
     * @param                        ProductCategoryRepository $productCategoryRepository
     * @param                        Request $request
     * @param                        Product $product
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function updateProduct(
        Product $product,
        Request $request,
        EntityManagerInterface $entityManager,
        ProductCategoryRepository $productCategoryRepository
    ) {


        $product->setProductnumber($product->getProductnumber());
        $product->setPrice($product->getPrice());
        $product->setImage(new File($this->getParameter('image_directory') . '/' . $product->getImage()));


        $form = $this->createForm(UpdateProductType::class, $product);
        $form->handleRequest($request);
        if ($this->isGranted('ROLE_ADMIN') && $form->isSubmitted() && $form->isValid()) {
            /**
             * @var Product $product
             */
            $file = $product->getImage();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            } $product = $form->getData();
            $product->setImage($fileName);

            $productCategory = $productCategoryRepository->findBy(
                [
                    'product' => $product->getId()
                ]
            );
            foreach ($productCategory as $proCategory) {
                $entityManager->remove($proCategory);
                $entityManager->flush();
            } $spaceReplace = $product->getUrlCustom();
            $string = preg_replace('/\s+/', '-', $spaceReplace);
            $product->setUrlCustom($string . $product->getProductnumber());
            $entityManager->merge($product);
            $entityManager->flush();
            $this->addFlash('success', 'Updated!');
            return $this->redirectToRoute('product_index');
        } return $this->render(
            'admin/updateproduct.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Symfony\Component\Routing\Annotation\Route("/delete/{id}", name="product_delete")
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Product $product)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_index');
    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
