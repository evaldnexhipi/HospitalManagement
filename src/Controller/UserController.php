<?php


namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Service;
use App\Form\ReservationFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function makeReservation(Service $service, Request $request, EntityManagerInterface $manager){
        $reservation = new Reservation();
        $reservation->setClient($this->getUser()->getClient());
        $reservation->setService($service);
        $form = $this->createForm(ReservationFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $reservation->setMedicalStaff($form->get('medicalStaff')->getData());
            $reservation->setDay($form->get('day')->getData());
            $reservation->setStatus($form->get('status')->getData());
            $manager->persist($reservation);
            $manager->flush();
            echo '<div style="background-color:white; color:black;">U shtua rezervimi</div>';
        }

        return $this->render('user/reservation.html.twig',[
            'reservationForm'=>$form->createView(),
        ]);
    }

}