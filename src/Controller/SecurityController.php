<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\Mailer;
class SecurityController extends AbstractController
{
    const DOUBLE_OPT_IN = false;
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
    public function register(Request $request, TokenGenerator $tokenGenerator, UserPasswordEncoderInterface $encoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator,  Mailer $mailer, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $client = new Client();
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            // encode the plain password
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $token = $tokenGenerator->generateToken();
            $user->setToken($token);
            $user->setIsActive(false);


            $user->setFirstName(ucfirst(strtolower($form->get('firstName')->getData())));
            $user->setLastName(ucfirst(strtolower($form->get('lastName')->getData())));

            $user->setEmail($form->get('email')->getData());
            $user->setGender($form->get('gender')->getData());
            $user->setBirthday($form->get('birthday')->getData());

            $teli = $form->get('telephone')->getData();

            $user->setTelephone($teli);
            $user->setAddress($form->get('address')->getData());

            $imageFile = $form->get('imageFilename')->getData();
            if($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(),PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try{
                    $imageFile->move(
                        $this->getParameter('user_images_directory'),
                        $newFilename
                    );
                }
                catch(FileException $e){
                    dd($e);
                }
                $user->setImageFilename($newFilename);
            }

            $user->setClient($client);
            $client->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($client);
            $em->flush();

            if (self::DOUBLE_OPT_IN == false) {
                $mailer->sendActivationEmailMessage($user);
                echo '<div style="background-color:red; color:white; text-align: center;">Ne ju dërguam një e-mail konfirmimi në adresën tuaj.</div>';
            }
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'errors'=>$form->getErrors()
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

    /**
     * @Route("/forgotPassword",name="app_forgot_password")
     */
    public function forgotPassword(Request $request, UserRepository $userRepository){
        $email = $request->request->get('email');
        $user = $userRepository->findOneBy(['email'=>$email]);

        if(!$user){
            $this->addFlash('noUserFound','Emaili nuk i takon MegaSpital!');
        }
        else {
            if (isset($_POST['dergoEmail'])) {
                /* Vendos Routerin Tend */
                //return $this->redirectToRoute('',['email'=>$email]);

            }
            if (isset($_POST['dergoSMS'])) {
                return $this->redirectToRoute('app_send_sms', ['email' => $email]);
            }
        }
        return $this->render('security/forgotPassword.html.twig',[]);
    }

}