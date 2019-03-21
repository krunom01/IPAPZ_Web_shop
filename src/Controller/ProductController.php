<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Form\UpdateProductType;
use App\Repository\CategoryRepository;
use App\Form\ProductFormType;
use App\Form\UserFormType;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("admin/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index")
     * @return Response
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
     * @Route("/new", name="product_new", methods={"GET","POST"})
     * @param         Request $request
     * @param         EntityManagerInterface $entityManager
     * @return        Response
     * @param         CategoryRepository $categoryRepository
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
            }
            $product->setImage($fileName);
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
     * @Route("/updateproduct/{id}", name="update_product")
     * @param                        EntityManagerInterface $entityManager
     * @param                        ProductCategoryRepository $productCategoryRepository
     * @param                        Request $request
     * @param                        Product $product
     * @return                       Response
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
            }
            $product = $form->getData();
            $product->setImage($fileName);

            $productCategory = $productCategoryRepository->findBy(
                [
                    'product' => $product->getId()
                ]
            );
            foreach ($productCategory as $proCategory) {
                $entityManager->remove($proCategory);
                $entityManager->flush();
            }
            $spaceReplace = $product->getUrlCustom();
            $string = preg_replace('/\s+/', '-', $spaceReplace);
            $product->setUrlCustom($string . $product->getProductnumber());
            $entityManager->merge($product);
            $entityManager->flush();
            $this->addFlash('success', 'Updated!');
            return $this->redirectToRoute('product_index');
        }
        return $this->render(
            'admin/updateproduct.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/delete/{id}", name="product_delete")
     */
    public function delete(Request $request, Product $product): Response
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


    /**
     * @Route  ("/newCategory/{id}" , name = "product_new_category")
     * @param  CategoryRepository $categoryRepository
     * @return Response
     */
    public function newCategory(
        $id,
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ) {

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductUpdateForm::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $productCategories = $form->getData();
            $cat = $productCategories->getCategory();
            foreach ($cat as $category) {
                /**
                 * @var ProductCategory $productCategory
                 */
                $productCategory = new ProductCategory();
                $productCategory->setProduct($product);
                $productCategory->setCategory($category);
                $entityManager->persist($productCategory);
                $entityManager->flush();
            }
        }


        return $this->render(
            "admin/newcategory.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }
}
