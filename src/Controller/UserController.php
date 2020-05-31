<?php


namespace App\Controller;


use App\Entity\Reservation;
use App\Entity\Results;
use App\Entity\Review;
use App\Entity\Service;
use App\Entity\User;

use App\Form\ReservationFormType;
use App\Form\ReviewFormType;
use App\Form\RoomFormType;
use App\Form\UserFormType;
use App\Repository\AnamnesisRepository;
use App\Repository\ClientRepository;
use App\Repository\PatientRepository;
use App\Repository\ReservationRepository;
use App\Repository\ResultsRepository;
use App\Repository\ReviewRepository;
use App\Repository\RoomRepository;
use App\Repository\TreatmentRepository;
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
    public function helloPerson(ReservationRepository $reservationRepository,ReviewRepository $reviewRepository, AnamnesisRepository $anamnesisRepository,ResultsRepository $resultsRepository){
        /* Rezervime */
        $reservationsNumber = (int)$reservationRepository->getReservationsNumberForUser($this->getUser()->getClient());
        $doneReservationsNumber = (int) $reservationRepository->getDoneReservationsNumberForUser($this->getUser()->getClient());
        /* Review */
        $reviewsNumber = (int) $reviewRepository->getReviewsNumberForUser($this->getUser()->getClient());

        /* Numri i Anamnezave */
        $anamnesisNumber = (int) $anamnesisRepository->getAnamnesisNumberForUser($this->getUser()->getClient());

        /* Shpenizmet */
        $expensesNumber = (int) $reservationRepository->getTotalCostForUser($this->getUser()->getClient());

        /* Numri i Rezultateve */
        $resultsNumber = (int) $resultsRepository->getResultsNumberForUser($this->getUser()->getClient());

        $top5Reservations = $reservationRepository->getTop5ReservationsForClient($this->getUser()->getClient());
        $lastResult = $resultsRepository->getLastResultForClient($this->getUser()->getClient());

        return $this->render('user/profile.html.twig',[
            'reservationsNumber'=>$reservationsNumber,
            'doneReservationsNumber'=>$doneReservationsNumber,
            'reviewsNumber'=>$reviewsNumber,
            'anamnesisNumber'=>$anamnesisNumber,
            'expensesNumber'=>$expensesNumber,
            'top5Reservations'=>$top5Reservations,
            'resultsNumber'=>$resultsNumber,
            'lastResult'=>$lastResult
        ]);
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
     * @Route("/listAnamneses",name="app_user_list_anamneses")
     */
    public function listAnamnesse (AnamnesisRepository $anamnesisRepository, Request $request, PaginatorInterface $paginator){
        $queryBuilder = $anamnesisRepository->getAnamnesesForClient($this->getUser()->getClient());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/userFunctionalities/list_anamneses.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/listResults",name="app_user_list_results")
     */
    public function listResults (ResultsRepository $resultsRepository, Request $request, PaginatorInterface $paginator){
        $queryBuilder = $resultsRepository->getResultsForClient($this->getUser()->getClient()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/userFunctionalities/list_results.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/downloadResult/{id}",name="app_user_down_result")
     */
    public function downloadResults(Results $results){
        $pdfPath = $this->getParameter('doc_results_directory').'/'.$results->getAnalysisPDF();

        return $this->file($pdfPath);
    }

    /**
     * @Route("/yourFeedback",name="app_user_feedback")
     */
    public function yourFeedback(Request $request, EntityManagerInterface $entityManager){
        $review = new Review();
        $review->setClient($this->getUser()->getClient());
        $form = $this->createForm(ReviewFormType::class,$review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash('reviewSuccess','Faleminderit Per Mendimin Tuaj!');
        }

        return $this->render('user/userFunctionalities/add_review.html.twig',[
            'form'=>$form->createView()
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