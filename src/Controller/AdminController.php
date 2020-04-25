<?php


namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/sendEmail/{name}")
     */
    public function helloAdmin($name, MailerInterface $mailer){
        $email = (new Email())
            ->from('evald@izz.ai')
            ->to('evaldnexhipi123@gmail.com')
            ->subject('Hello Test')
            ->text('Pershendetje '.$name);

        $mailer->send($email);



        return new Response("Email sent to ".$name);
    }
}