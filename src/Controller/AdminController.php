<?php


namespace App\Controller;
use App\Entity\Reservation;
use App\Form\AprovoRezervimFormType;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/profile",name="app_admin_profile")
     */
    public function showProfile(){

        return $this->render('user/admin/admin_profile.html.twig');
    }

    /**
     * @Route("/reservationssoon",name="app_admin_reservations_soon")
     */
    public function listReservationsSoon(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){
        $reservations = $reservationRepository->findAll();

//        $q = $request->query->get('q');
        $queryBuilder = $reservationRepository->getAllSoonReservations();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/admin/rezervimet_soon.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/reservationssoon/approve/{id}",name="app_admin_reservations_soon_approve")
     */
    public function approveReservation(Reservation $reservation, Request $request){
        $client = $reservation->getClient()->getUser()->getFirstName();
        $doctor = $reservation->getMedicalStaff()->getUser()->getFirstName();
        

        return $this->render('user/admin/aprovo_rezervim.html.twig',[

        ]);
//        return new Response("hello service with id: ".$reservation->getId()." and user: ".$reservation->getClient()->getUser()->getFirstName());
    }
}