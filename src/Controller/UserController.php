<?php


namespace App\Controller;


use App\Entity\Service;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class UserController extends BaseController
{
    /**
     * @Route("/",name="app_profile_main")
     */
    public function helloPerson(){
        return new Response("<h1> Hello ".$this->getUser()->getEmail()."</h1>");
    }

    /**
     * @Route("/makeReservation/{id}",name="app_profile_reservation")
     */
    public function makeReservation(Service $service){

    }
}