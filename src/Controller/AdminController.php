<?php


namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController
{
    /**
     * @Route("/")
     */
    public function helloAdmin(){
        return new Response("<h1> Hello ".$this->getUser()->getEmail()."</h1>");
    }
}