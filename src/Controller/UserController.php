<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class UserController extends BaseController
{
    /**
     * @Route("/")
     */
    public function helloPerson(){
        return new Response("<h1> Hello ".$this->getUser()->getEmail()."</h1>");
    }
}