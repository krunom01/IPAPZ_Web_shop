<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserEditType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Symfony\Component\Routing\Annotation\Route("/login", name="app_login")
     * @Sensio\Bundle\FrameworkExtraBundle\Configuration\Security("not   is_granted('ROLE_USER')")
     * @param           AuthenticationUtils $authenticationUtils
     * @return          Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Symfony\Component\Routing\Annotation\Route("/register", name="app_register")
     * @param              Request $request
     * @param              UserPasswordEncoderInterface $passwordEncoder
     * @param              GuardAuthenticatorHandler $guardHandler
     * @param              LoginFormAuthenticator $authenticator
     * @param              EntityManagerInterface $entityManager
     * @return             null|Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
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
     * @param             EntityManagerInterface $entityManager
     * @param             Request $request
     * @return            null|Response
     */
    public function profile(
        Request $request,
        LoginFormAuthenticator $authenticator,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        EntityManagerInterface $entityManager
    ) {

        $user = $this->getUser();
        $form = $this->createForm(UserEditType::class, $user);
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

            $this->addFlash('success', 'Profile updated!');
        } return $this->render(
            'security/useredit.html.twig',
            ['userEdit' => $form->createView(),]
        );
    }
}
