<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\User;
use App\Form\CustomPageType;
use App\Form\CouponFormType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CouponRepository;
use App\Repository\CustomPageRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserFormType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/", name="admin")
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {

        return $this->render(
            'admin/base.html.twig',
            [
                'title' => 'Admin panel',
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/users", name="users")
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function users()
    {


        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render(
            'admin/users.html.twig',
            [
                'title' => 'User list',
                'users' => $users,
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/userEdit/{id}", name ="admin_user_edit")
     * @param  Request $request
     * @param  EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */


    public function editUser(Request $request, EntityManagerInterface $entityManager, User $user)
    {

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully edited!');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render(
            'admin/edituser.html.twig',
            [
                'title' => 'Edit list',
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/userOrder/{id}", name ="admin_user_order")
     * @param $id
     * @param OrderRepository $orderRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function showOrder(
        $id,
        OrderRepository $orderRepository
    ) {
        $userOrder = $orderRepository->findOneBy(['id' => $id]);

        $items = $userOrder->getOrderedItems();

        return $this->render(
            'admin/orderDetails.html.twig',
            [
                'title' => 'User Order',
                'items' => $items,
                'userOrder' => $userOrder
            ]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/orderUpdate/{id}", name ="admin_order_update")
     * @param $id
     * @param OrderRepository $orderRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function updateOrder(
        $id,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ) {
        $userOrder = $orderRepository->findOneBy(['id' => $id]);
        $userOrder->setStatus('paid');
        $entityManager->persist($userOrder);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully updated Order!');
        return $this->redirectToRoute('admin_orders');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/categoryProducts/{id}", name ="admin_category_products")
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function categoryProducts($id)
    {

        $em = $this->getDoctrine()->getManager();
        $sql = "select a.name, a.image, a.name, a.price, a.id
                from product a
                inner join product_category pc on a.id = pc.product_id
                where category_id = :id";
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('id', $id);
        $statement->execute();
        $products = $statement->fetchAll();


        return $this->render(
            'admin/categoryProducts.html.twig',
            [
                'products' => $products,
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/customPages", name ="admin_custom_pages")
     * @param  $customPageRepository CustomPageRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function customPages(CustomPageRepository $customPageRepository)
    {


        return $this->render(
            'admin/customPages.html.twig',
            [
                'customPages' => $customPageRepository->findAll()
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/customPages/new", name ="customPage_new")
     * @param  $customPageRepository CustomPageRepository
     * @param  EntityManagerInterface $entityManager
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function customPagesNew(
        Request $request,
        EntityManagerInterface $entityManager,
        CustomPageRepository $customPageRepository
    ) {
        $form = $this->createForm(CustomPageType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var CustomPage $customPage
             */
            $customPage = $form->getData();
            $entityManager->persist($customPage);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new custom page!');
            return $this->redirectToRoute('admin_custom_pages');
        }


        return $this->render(
            'admin/customPagesNew.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/coupons", name ="admin_coupons")
     * @param  CouponRepository $couponRepository
     * @param  EntityManagerInterface $entityManager
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function coupons(Request $request, EntityManagerInterface $entityManager, CouponRepository $couponRepository)
    {

        $form = $this->createForm(CouponFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $req = $request->request->all();
            $discount = $req['coupon_form']['discount'];
            $code = $this->strRandom(6);
            $coupon = new Coupon();
            $coupon->setCode($code);
            $coupon->setDiscount($discount);
            $entityManager->persist($coupon);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new coupon!');
            return $this->redirectToRoute('admin_coupons');
        } return $this->render(
            'admin/coupons.html.twig',
            ['coupons' => $couponRepository->findAll(), 'form' => $form->createView()]
        );
    }

    /**
     * @param int $length
     * @return bool|string
     */
    public function strRandom($length = 6)
    {
        $numbers = '0123456789';
        return substr(str_shuffle(str_repeat($numbers, $length)), 0, $length);
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/coupons/delete/{id}", name="coupon_delete")
     * @param                               Coupon $coupon
     * @param                               EntityManagerInterface $entityManager
     * @return                              \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCoupon(Coupon $coupon, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($coupon);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted!');
        return $this->redirectToRoute('admin_coupons');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/orders", name ="admin_orders")
     * @param  OrderRepository $orderRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function adminOrders(
        OrderRepository $orderRepository
    ) {
        return $this->render(
            'admin/allOrders.html.twig',
            [
                'orders' => $orderRepository->findAll(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/ordersPdf/{id}", name ="admin_orders_pdf")
     * @param Order $order
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function createPdf(
        Order $order,
        EntityManagerInterface $entityManager
    ) {

        $pdf = new Options();
        $pdf->set('defaultFont', 'Arial');
        $domPdf = new Dompdf($pdf);
        $items = $order->getOrderedItems();
        $page = $this->renderView(
            'admin/orderPDF.html.twig',
            [
                'userOrder' => $order,
                'items' => $items
            ]
        );
        $fileName = $order->getId().'.pdf';
        $domPdf->loadHtml($page);
        $domPdf->setPaper('A4', 'portrait');
        $domPdf->render();
        $output = $domPdf->output();
        $dir = '../public/pdf/';
        $path =  $dir . $fileName;
        file_put_contents($path, $output);
        $order->setPdf(1);
        $entityManager->persist($order);
        $entityManager->flush();

        return $this->render(
            'admin/orderPDF.html.twig',
            [
                'userOrder' => $order,
                'items' => $items
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/order/download/{file}",name="order_download")
     * @param $file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPdf($file)
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/pdf/' . $file . '.pdf';
        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set(
            'Content-Disposition',
            sprintf('attachment; filename="%s"', $file)
        );
        $response->setContent(file_get_contents($filePath));
        $response->setStatusCode(200);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }
}
