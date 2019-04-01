<?php

namespace App\Controller;

use App\Entity\CountryShipping;
use App\Entity\Coupon;
use App\Entity\CustomPage;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\PaymentType;
use App\Form\CustomPageType;
use App\Form\CouponFormType;
use App\Form\ImportShippingCSVType;
use App\Form\PaymentTypeFormType;
use App\Form\ShippingCountryType;
use App\Repository\OrderRepository;
use App\Repository\PaymentTypeRepository;
use App\Repository\CountryShippingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CouponRepository;
use App\Repository\CustomPageRepository;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use League\Csv\Reader;
use Knp\Component\Pager\PaginatorInterface;

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
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function users(
        PaginatorInterface $paginator,
        Request $request
    ) {

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render(
            'admin/users.html.twig',
            [
                'title' => 'User list',
                'users' => $pagination,
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
     * @Symfony\Component\Routing\Annotation\Route("/admin/deleteOrder/{id}", name ="admin_order_delete")
     * @param $id
     * @param OrderRepository $orderRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function deleteOrder(
        $id,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ) {
        $userOrder = $orderRepository->findOneBy(['id' => $id]);
        $entityManager->remove($userOrder);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted Order!');
        return $this->redirectToRoute('admin_orders');
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
     * @param  EntityManagerInterface $entityManager
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function customPagesNew(
        Request $request,
        EntityManagerInterface $entityManager
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
            'admin/newCustomPage.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/customPages/{id}", name ="customPage_edit")
     * @param CustomPage $customPage
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function customPageEdit(
        CustomPage $customPage,
        EntityManagerInterface $entityManager,
        Request $request
    ) {


        $form = $this->createForm(CustomPageType::class, $customPage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customPage = $form->getData();
            $entityManager->persist($customPage);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully edited!');
            return $this->redirectToRoute('admin_custom_pages');
        }

        return $this->render(
            'admin/editCustomPage.html.twig',
            [

                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/customPageDelete/{id}", name ="customPage_delete")
     * @param CustomPage $customPage
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function customPageDelete(
        CustomPage $customPage,
        EntityManagerInterface $entityManager
    ) {


        $entityManager->remove($customPage);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted custom page!');
        return $this->redirectToRoute('admin_custom_pages');
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
            $this->addFlash('success', 'Successfully added new Coupon!');
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
     * @param      PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function adminOrders(
        OrderRepository $orderRepository,
        PaginatorInterface $paginator,
        Request $request
    ) {
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            $orders = $orderRepository->findByEmail($email);
        } elseif (isset($_GET['name'])) {
            $orders = $orderRepository->findByName($_GET['name']);
        } elseif (isset($_GET['dateStart']) and isset($_GET['dateEnd'])) {
            $orders = $orderRepository->getRange($_GET['dateStart'], $_GET['dateEnd']);
        } elseif (isset($_GET['dateStart']) and isset($_GET['dateEnd'])) {
            $orders = $orderRepository->getRange($_GET['dateStart'], $_GET['dateEnd']);
        } else {
            $orders = $orderRepository->findAll();
        };
        $pagination = $paginator->paginate(
            $orders, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render(
            'admin/allOrders.html.twig',
            [
                'orders' => $pagination,
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
        $fileName = $order->getId() . '.pdf';
        $domPdf->loadHtml($page);
        $domPdf->setPaper('A4', 'portrait');
        $domPdf->render();
        $output = $domPdf->output();
        $dir = '../public/pdf/';
        $path = $dir . $fileName;
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

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/payments", name="admin_payments")
     * @param PaymentTypeRepository $paymentTypeRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function payments(
        PaymentTypeRepository $paymentTypeRepository
    ) {

        return $this->render(
            'admin/paymentType.html.twig',
            [
                'payments' => $paymentTypeRepository->findAll()
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/newPaymentType", name="admin_newPayments")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function newPayment(
        EntityManagerInterface $entityManager,
        Request $request
    ) {

        $paymentType = new PaymentType();
        $form = $this->createForm(PaymentTypeFormType::class, $paymentType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paymentType);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new payment type!');
            return $this->redirectToRoute('admin_payments');
        }

        return $this->render(
            'admin/editCustomPage.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/editPaymentType/{id}", name="admin_editPayment")
     * @param Request $request
     * @param PaymentType $paymentType
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function editPayment(
        EntityManagerInterface $entityManager,
        Request $request,
        PaymentType $paymentType
    ) {

        $form = $this->createForm(PaymentTypeFormType::class, $paymentType);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paymentType);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully updated payment type!');
            return $this->redirectToRoute('admin_payments');
        }

        return $this->render(
            'admin/editCustomPage.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/deletePaymentType/{id}", name="admin_deletePayment")
     * @param PaymentType $paymentType
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function deletePayment(
        EntityManagerInterface $entityManager,
        PaymentType $paymentType
    ) {
        $entityManager->remove($paymentType);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted payment type!');
        return $this->redirectToRoute('admin_payments');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/updatePaymentVisibility/{id}", name="admin_updatePayment")
     * @param PaymentType $paymentType
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function updatePayment(
        EntityManagerInterface $entityManager,
        PaymentType $paymentType
    ) {
        if ($paymentType->getVisibility() == true) {
            $paymentType->setVisibility(false);
            $entityManager->persist($paymentType);
            $entityManager->flush();
        } else {
            $paymentType->setVisibility(true);
            $entityManager->persist($paymentType);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Successfully updated payment type visibility!');
        return $this->redirectToRoute('admin_payments');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/shippingCSV", name="admin_csv")
     * @param Request $request
     * @param CountryShippingRepository $countryRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function newCsv(
        Request $request,
        EntityManagerInterface $entityManager,
        CountryShippingRepository $countryRepository
    ) {
        $formCsv = $this->createForm(ImportShippingCSVType::class);
        $formCsv->handleRequest($request);
        if ($this->isGranted('ROLE_ADMIN') && $formCsv->isSubmitted() && $formCsv->isValid()) {
            $csvFile = $formCsv->get('file')->getData();
            $ext = $csvFile->getClientOriginalExtension();
            if ($ext === "csv") {
                $path = $csvFile->getPathName();
                $reader = Reader::createFromPath($path);
                $reader->setHeaderOffset(0);
                $records = $reader->getRecords();
                $header = $reader->getHeader();
                $values = array("country", "code", "price");
                $result = array_diff($header, $values);
                if (empty($result)) {
                    foreach ($records as $rec) {
                        $existCountry = $countryRepository->findOneBy(['country' => $rec['country']]);
                        if (!$existCountry) {
                            $newShipping = new CountryShipping();
                            $newShipping->setCountry($rec['country']);
                            $newShipping->setCountryCode($rec['code']);
                            $newShipping->setShippingPrice($rec['price']);
                            $entityManager->persist($newShipping);
                            $entityManager->flush();
                        }
                    }
                } else {
                    $this->addFlash('success', 'please insert CSV file with country, code and price!');
                }
            } else {
                $this->addFlash('success', 'please insert CSV file!');
            }
        }

        return $this->render(
            'admin/newCSV.html.twig',
            [
                'form' => $formCsv->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/newShippingCountry", name="admin_newShippingConutry")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function newShippingCountry(
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        $newCounutry = new CountryShipping();
        $form = $this->createForm(ShippingCountryType::class, $newCounutry);
        $form->handleRequest($request);
        if ($this->isGranted('ROLE_ADMIN') && $form->isSubmitted() && $form->isValid()) {
            $nullPrice = $form->get('shippingPrice')->getData();

            if ($nullPrice == null) {
                $newCounutry->setShippingPrice(10);
            }

            $entityManager->persist($newCounutry);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new Country');
            return $this->redirectToRoute('admin_ShippingCountries');
        }

        return $this->render(
            'admin/newCountry.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/ShippingCountries", name="admin_ShippingCountries")
     * @param CountryShippingRepository $countryRepository
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */

    public function shippingCountries(
        CountryShippingRepository $countryRepository
    ) {

        return $this->render(
            'admin/shippingCountries.html.twig',
            [
                'countries' => $countryRepository->findAll(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/deleteCountry/{id}", name="admin_deleteCountry")
     * @param CountryShipping $countryShipping
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteCountry(
        EntityManagerInterface $entityManager,
        CountryShipping $countryShipping
    ) {
        $entityManager->remove($countryShipping);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted country!');
        return $this->redirectToRoute('admin_ShippingCountries');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/editCountry/{id}", name="admin_editCountry")
     * @param Request $request
     * @param CountryShipping $countryShipping
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function editCountry(
        EntityManagerInterface $entityManager,
        Request $request,
        CountryShipping $countryShipping
    ) {

        $form = $this->createForm(ShippingCountryType::class, $countryShipping);
        $form->handleRequest($request);
        if ($this->isGranted('ROLE_ADMIN') && $form->isSubmitted() && $form->isValid()) {
            $nullPrice = $form->get('shippingPrice')->getData();

            if ($nullPrice == null) {
                $countryShipping->setShippingPrice(10);
            }

            $entityManager->persist($countryShipping);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully added new Country');
            return $this->redirectToRoute('admin_ShippingCountries');
        }

        return $this->render(
            'admin/editCountry.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
