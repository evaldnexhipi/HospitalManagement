<?php


namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctor")
 */
class DocController extends BaseController
{
    /**
     * @Route("/",name="app_profile_doctor")
     */
    public function helloPerson(){
        return $this->render('user/doctorProfile.html.twig');
    }

}