<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserChangePasswordType;
use App\Form\UserEditType;
use App\Form\UserFormType;
use App\Repository\CustomPageRepository;
use App\Repository\OrderRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 *
 * @package App\Controller
 *
 * Security annotation on login will throw 403
 * and on register route we use redirect to route. Both examples are correct.
 */
class UserController extends AbstractController
{

    /**
     * @Symfony\Component\Routing\Annotation\Route("/login/", name="app_login")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("not   is_granted('ROLE_USER')")
     * @param           AuthenticationUtils $authenticationUtils
     * @param      CustomPageRepository $customPageRepository,
     * @return           \Symfony\Component\HttpFoundation\Response
     */
    public function login(
        AuthenticationUtils $authenticationUtils,
        CustomPageRepository $customPageRepository
    ) {
        if ($this->isGranted('ROLE_USER') or $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        } $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername,
                'error' => $error, 'customPages' => $customPageRepository->findAll()
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/register", name="app_register")
     * @param              Request $request
     * @param              UserPasswordEncoderInterface $passwordEncoder
     * @param              GuardAuthenticatorHandler $guardHandler
     * @param              LoginFormAuthenticator $authenticator
     * @param      CustomPageRepository $customPageRepository
     * @param              EntityManagerInterface $entityManager
     * @return           \Symfony\Component\HttpFoundation\Response            null|Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        CustomPageRepository $customPageRepository
    ) {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        } $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        } return $this->render(
            'security/register.html.twig',
            [
                'registrationForm' => $form->createView(),
                'customPages' => $customPageRepository->findAll()

            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/users/delete/{id}", name="user_delete")
     * @param                             User $user
     * @param                             EntityManagerInterface $entityManager
     * @return                            \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', 'Successfully deleted!');
        return $this->redirectToRoute('users');
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile", name="profile")
     * @param CustomPageRepository $customPageRepository
     * @return           \Symfony\Component\HttpFoundation\Response           null|Response
     */
    public function profile(
        CustomPageRepository $customPageRepository
    ) {
        $user = $this->getUser();

        return $this->render(
            'home/profile.html.twig',
            [
                'user' => $user,
                'customPages' => $customPageRepository->findAll(),
            ]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile/edit", name="profile_edit")
     * @param             EntityManagerInterface $entityManager
     * @param             Request $request
     * @param CustomPageRepository $customPageRepository
     * @return           \Symfony\Component\HttpFoundation\Response            null|Response
     */
    public function editProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        CustomPageRepository $customPageRepository
    ) {

        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated!');
            return $this->redirectToRoute('profile');
        } return $this->render(
            'security/useredit.html.twig',
            ['userEdit' => $form->createView(), 'customPages' => $customPageRepository->findAll()]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile/changePassword", name="changePassword")
     * @param             EntityManagerInterface $entityManager
     * @param             UserPasswordEncoderInterface $passwordEncoder
     * @param CustomPageRepository $customPageRepository
     * @param             Request $request
     * @return           \Symfony\Component\HttpFoundation\Response           null|Response
     */
    public function changePassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        CustomPageRepository $customPageRepository
    ) {
        $user = $this->getUser();
        $form = $this->createForm(UserChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Password changed!');
            return $this->redirectToRoute('profile');
        } return $this->render(
            'security/userChangePassword.html.twig',
            ['userEdit' => $form->createView(),'customPages' => $customPageRepository->findAll()]
        );
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile/wishlist", name="wishList")
     * @param CustomPageRepository $customPageRepository
     * @return           \Symfony\Component\HttpFoundation\Response            null|Response
     */
    public function userWishList(
        CustomPageRepository $customPageRepository
    ) {
        $user = $this->getUser();

        return $this->render(
            'home/userWishList.html.twig',
            ['wishlist' => $user->getWish(),'customPages' => $customPageRepository->findAll()]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile/orders", name="orders")
     * @param OrderRepository $orderRepository
     * @param CustomPageRepository $customPageRepository
     * @return           \Symfony\Component\HttpFoundation\Response            null|Response
     */
    public function userOrders(
        OrderRepository $orderRepository,
        CustomPageRepository $customPageRepository
    ) {
        $user = $this->getUser();

        return $this->render(
            'home/userOrders.html.twig',
            ['userOrders' => $orderRepository->findUserOrders($user),
                'customPages' => $customPageRepository->findAll()
            ]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/profile/order/{id}", name="user_order_details")
     * @param OrderRepository $orderRepository
     * @param $id
     * @param CustomPageRepository $customPageRepository
     * @return           \Symfony\Component\HttpFoundation\Response           null|Response
     */
    public function userOrderDetails(
        OrderRepository $orderRepository,
        CustomPageRepository $customPageRepository,
        $id
    ) {
        $user = $this->getUser();

        $userItems = $orderRepository->findOneBy(
            ['user' => $user,
             'id' => $id
            ]
        );
        $nesto = $userItems->getOrderedItems();

        return $this->render(
            'home/userOrder.html.twig',
            ['userItems' => $userItems->getOrderedItems(),
                'customPages' => $customPageRepository->findAll()
            ]
        );
    }
    /**
     * @Symfony\Component\Routing\Annotation\Route("/admin/userEdit/{id}", name ="admin_user_edit")
     * @param  Request $request
     * @param User $user
     * @param  EntityManagerInterface $entityManager
     * @return           \Symfony\Component\HttpFoundation\Response
     */


    public function editUser(
        Request $request,
        EntityManagerInterface $entityManager,
        User $user
    ) {

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Successfully edited!');
            return $this->redirectToRoute('users');
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
}


