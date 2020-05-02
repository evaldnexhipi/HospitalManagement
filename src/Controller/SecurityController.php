<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Date;

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
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             LoginFormAuthenticator $authenticator,
                             EntityManagerInterface $entityManager)
    {
        $user = new User();
        $client = new Client();
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setFirstName(ucfirst(strtolower($form->get('firstName')->getData())));
            $user->setLastName(ucfirst(strtolower($form->get('lastName')->getData())));

            $user->setEmail($form->get('email')->getData());
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setGender($form->get('gender')->getData());
            $user->setBirthday($form->get('birthday')->getData());

            if (!is_numeric($form->get('telephone')->getData())){
                $form->addError(new FormError('Telefoni te permbaje numra','telephone','telephone','telephone'));
            }

            $user->setTelephone($form->get('telephone')->getData());
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

            $entityManager->persist($user);
            $entityManager->persist($client);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'errors'=>$form->getErrors()
        ]);
    }
}
