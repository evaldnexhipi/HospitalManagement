<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TelephoneController extends BaseController
{

    /**
     * @Route("/sendSMS/{email}",name="app_send_sms")
     */
    public function sendMessage ($email,UserRepository $userRepository,Request $request,EntityManagerInterface $entityManager){
        $user = $userRepository->findOneBy(['email'=>$email]);
        $teli = $user->getTelephone();

/*
        $basic = new \Nexmo\Client\Credentials\Basic('2914317d','4MDI85HI34xWdAwA');
        $client = new \Nexmo\Client($basic);
*/

        $random_number = mt_rand(10000,60000);
        $user->setSmsCode($random_number);

/*
        $message = $client->message()
            ->send([
                'to'=>'+355'.substr($teli,1),
                'from'=>'Vonage SMS API',
                'text'=>'MegaSpital! Kodi i konfirmimit eshte: '.$random_number
            ]);
  */

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('security/smsSent.html.twig',[
            'email'=>$email
        ]);
    }

    /**
     * @Route("/checkSMS/{email}",name="app_check_sms")
     */
    public function checkConfirmation ($email,Request $request, UserRepository $userRepository){
        $confirmationNumber = $request->request->get('smsCode');
        $user = $userRepository->findOneBy(['email'=>$email]);
        if ($confirmationNumber == $user->getSmsCode()){
            return $this->render('security/resetPassword.html.twig',[
                'email'=>$email,
                'notSuccess'=>true
            ]);
        }

        $this->addFlash('notice','Kodi nuk perputhet');

        return $this->render('security/smsSent.html.twig',[
            'email'=>$email
        ]);
    }

    /**
     * @Route("/renewPassword/{email}",name="app_renew_password")
     */
    public function renewPassword($email,Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder){
        $password = $request->request->get('newPassword');
        $user = $userRepository->findOneBy(['email'=>$email]);

        if ($password==""){
            $this->addFlash('errorPassword','Passwordi nuk duhet bosh');
            $notSuccess = true;
        }
        else if (!preg_match('/[0-9]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password)){
            $this->addFlash('errorPassword','Passwordi te permbaje [A-z]|[a-z]|[0-9]');
            $notSuccess=true;
        }
        else {
            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Fjalekalimi u ndryshua');
            $notSuccess=false;
        }
        return $this->render('security/resetPassword.html.twig',[
            'email'=>$email,
            'notSuccess'=>$notSuccess
        ]);

    }
}