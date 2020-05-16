<?php


namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctor")
 */
class DocController extends BaseController
{
    /**
     * @Route("/",name="app_doc_profile")
     */
    public function helloPerson(){
        return $this->render('user/doctorProfile.html.twig');
    }

}