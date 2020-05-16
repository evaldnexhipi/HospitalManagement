<?php


namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Service;
use App\Entity\User;

use App\Form\ReservationFormType;
use App\Form\UserFormType;
use App\Repository\ReservationRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use App\Form\RegistrationFormType;
/**
 * @Route("/profile")
 */
class UserController extends BaseController
{
    /**
     * @Route("/",name="app_profile_main")
     */
    public function helloPerson(){
        return $this->render('user/profile.html.twig');
    }

    /**
     * @Route("/makeReservation/{id}",name="app_profile_reservation")
     */
    public function makeReservation(Service $service, Request $request, EntityManagerInterface $manager)
    {
        $reservation = new Reservation();
        $reservation->setClient($this->getUser()->getClient());
        $reservation->setService($service);
        $form = $this->createForm(ReservationFormType::class);
        $form->handleRequest($request);

        $price=$service->getCost();
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setMedicalStaff($form->get('medicalStaff')->getData());
            $reservation->setDay($form->get('day')->getData());
            $reservation->setStatus('pritje');
            $manager->persist($reservation);
            $manager->flush();
            $this->addFlash('successReservation','U shtua rezervimi ne pritje');
        }

        return $this->render('user/reservation.html.twig', [
            'reservationForm' => $form->createView(),
            'cost'=>$price
        ]);
    }

    /**
     * @Route("/makeReservation/paypal/{serviceID}/{doctorID}/{date}",name="app_process_paypal")
     */
    public function processPayPal($serviceID, $doctorID, $date){

    }

    /**
     * @Route("/reservationssoon",name="app_user_reservations_soon")
     */
    public function showReservationsSoon (ReservationRepository $reservationRepository,Request $request, PaginatorInterface $paginator){
//        $reservations = $reservationRepository->findBy(["client"=>$this->getUser()]);
        $queryBuilder = $reservationRepository->getAllSoonReservationsForClient($this->getUser()->getClient()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/userFunctionalities/rezervimet_soon.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/reservationsdone",name="app_user_reservations_done")
     */
    public function showReservationsDone (ReservationRepository $reservationRepository,Request $request, PaginatorInterface $paginator){
//        $reservations = $reservationRepository->findBy(["client"=>$this->getUser()]);
        $queryBuilder = $reservationRepository->getAllDoneReservationsForClient($this->getUser()->getClient()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/userFunctionalities/rezervimet_done.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/reservation/cancel/{id}",name="app_user_cancel_reservation")
     */
    public function cancelReservation(Reservation $reservation, EntityManagerInterface $entityManager){
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('canceled','Rezervimi u anulua');
        return $this->redirectToRoute('app_user_reservations_soon');
    }

    /**
     * @Route("/manage",name="app_user_manage")
     */
    public function manageProfile(Request $request, EntityManagerInterface $entityManager) {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user/userFunctionalities/manage.html.twig',[
            'userForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/addAdminRole",name="app_add_admin_role")
     */
    public function addAdminRole (EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $user->addRole('ROLE_ADMIN');
        $entityManager->flush();
        return new Response('U shtua roli i admin per '.$user->getEmail());
    }
}