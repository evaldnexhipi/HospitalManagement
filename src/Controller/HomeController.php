<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/departament/{number}",name="admin_profile")
     */
    public function getDepartament($number, EntityManagerInterface $em){
        $dep = $em->getRepository('App:Departament')->findOneBy(['name'=>'Departamenti_'.$number]);
        $arr = [];
        $arr [] = ['name' => $dep->getName()];
        $arr [] = ['description' => $dep->getDescription()];
        $arr [] = ['createdAt' => $dep->getCreatedAt()];
        $arr [] = ['updatedAt' => $dep->getUpdatedAt()];

        return new JsonResponse($arr,200);
    }

}