<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/",name="homepage")
     */
    public function homepage(){
        return $this->render('Home/homepage.html.twig');
    }

    /**
     * @Route("/admin",name="admin_profile")
     */
    public function getAdmin(){
        $arr = [];
        $arr[] = ['name' => 'Evald'];
        $arr[] = ['surname' =>'Nexhipi'];
        return new JsonResponse($arr,200);
    }

}