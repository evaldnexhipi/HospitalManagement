<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\TokenGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Date;
use App\Service\Mailer;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout(){
        throw  new \Exception('will be interepted after cApiTomming here');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, TokenGenerator $tokenGenerator, UserPasswordEncoderInterface $encoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,  Mailer $mailer): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            // encode the plain password
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $token = $tokenGenerator->generateToken();
            $user->setToken($token);
            $user->setIsActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            if (self::DOUBLE_OPT_IN == false) {
                $mailer->sendActivationEmailMessage($user);
                echo '<div style="background-color:red; color:white; text-align: center;">Ne ju dërguam një e-mail konfirmimi në adresën tuaj.</div>';
            }


        }
        return $this->render('registration/register.html.twig.', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/activate/{token}", name="activate")
     */
    public function activate(Request $request, User $user, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator)
    {
        $user->setIsActive(true);
        $user->setToken(null);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'user.welcome');

        // automatic login
        return $authenticatorHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $loginFormAuthenticator,
            'main'
        );
    }
}
