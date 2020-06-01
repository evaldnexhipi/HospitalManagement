<?php


namespace App\Controller;
use App\Entity\Anamnesis;
use App\Entity\Reservation;
use App\Entity\Results;
use App\Entity\Treatment;
use App\Entity\User;
use App\Form\AnamnesisFormType;
use App\Form\ResultsFormType;
use App\Form\TreatmentFormType;
use App\Form\UserFormType;
use App\Repository\AnamnesisRepository;
use App\Repository\ClientRepository;
use App\Repository\ReservationRepository;
use App\Repository\ResultsRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctor")
 */
class DocController extends BaseController
{
    /**
     * @Route("/",name="app_doc_profile")
     */
    public function helloPerson(ReservationRepository $reservationRepository, AnamnesisRepository $anamnesisRepository,ResultsRepository $resultsRepository, ReviewRepository $reviewRepository){
        /* Rezervime */
        $reservationsNumber = (int)$reservationRepository->getReservationsNumberForDoc($this->getUser()->getMedicalStaff());
        $doneReservationsNumber = (int) $reservationRepository->getDoneReservationsNumberForDoc($this->getUser()->getMedicalStaff());
        $reservationsDifference = (int) $reservationRepository->getReservationsNumberToPrevMonthForUser($this->getUser()) - $reservationRepository->getReservationsNumberTo2PrevMonthsForUser($this->getUser());
        /* Reviews */
        $reviewsNumber = (int) $reviewRepository->getReviewsNumber();

        /* Numri i Anamnezave */
        $anamnesisNumber = (int) $anamnesisRepository->getAnamnesisNumberForDoc($this->getUser()->getMedicalStaff());

        /* Shpenizmet */
        $expensesNumber = (int) $reservationRepository->getTotalCostForDoc($this->getUser()->getMedicalStaff());

        /* Numri i Rezultateve */
        $resultsNumber = (int) $resultsRepository->getResultsNumberForDoc($this->getUser()->getMedicalStaff());

        $top5Reservations = $reservationRepository->getTop5ReservationsForDoc($this->getUser()->getMedicalStaff());
        $lastResult = $resultsRepository->getLastResultForDoc($this->getUser()->getMedicalStaff());


        return $this->render('user/doctorProfile.html.twig',[
            'reservationsNumber'=>$reservationsNumber,
            'doneReservationsNumber'=>$doneReservationsNumber,
            'anamnesisNumber'=>$anamnesisNumber,
            'expensesNumber'=>$expensesNumber,
            'top5Reservations'=>$top5Reservations,
            'resultsNumber'=>$resultsNumber,
            'lastResult'=>$lastResult,
            'reviewsNumber'=>$reviewsNumber,
            'reservationsDifference'=>$reservationsDifference
        ]);
    }

    /**
     * @Route("/manage",name="app_doc_manage_profile")
     */
    public function manageProfile (Request $request, EntityManagerInterface $entityManager){
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('user/doc/manage_profile.html.twig',[
            'userForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/reservationsSoon",name="app_doc_soon_reservations")
     */
    public function listSoonReservations(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){
        //        $q = $request->query->get('q');
        $queryBuilder = $reservationRepository->getAllSoonReservationsForStaff($this->getUser()->getMedicalStaff()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/doc/rezervimet_soon.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/reservationsDone",name="app_doc_done_reservations")
     */
    public function listDoneReservations(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){
        //        $q = $request->query->get('q');
        $queryBuilder = $reservationRepository->getAllDoneReservationsForStaff($this->getUser()->getMedicalStaff()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/doc/rezervimet_done.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/deleteReservation/{id}",name="app_doc_delete_reservation")
     */
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $entityManager){
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash('deleteReservation','Rezervimi u fshi me sukses');
        return $this->redirectToRoute("app_doc_done_reservations");
    }

    /**
     * @Route("/listAnamnesis",name="app_doc_list_anamnesis")
     */
    public function listAnamnesis (ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){


    }


    /**
     * @Route("/makeAppointment/{id}",name="app_doc_make_appointment")
     */
    public function makeAppointment(Reservation $reservation,Request $request, EntityManagerInterface $entityManager){
        $anamnesis = new Anamnesis();
        $treatment = new Treatment();

        $anamnesis->setClient($reservation->getClient());
        $anamnesis->setMedicalStaff($reservation->getMedicalStaff());
        $anamnesisForm = $this->createForm(AnamnesisFormType::class,$anamnesis);
        unset($treatmentForm);
        $treatmentForm = $this->createForm(TreatmentFormType::class);

        //if
        if (isset($_POST['anamnesisButton'])) {
            $anamnesisForm->handleRequest($request);
            if ($anamnesisForm->isSubmitted() && $anamnesisForm->isValid()){
                $anamnesis = $anamnesisForm->getData();

                $entityManager->persist($anamnesis);
                $reservation->setStatus('kryer');
                $entityManager->flush();
                $this->addFlash('anamnesisSuccess','Anamneza u shtua me sukses');
            }
        }

        if (isset($_POST['treatmentButton'])) {
            $treatmentForm->handleRequest($request);
            if ($treatmentForm->isSubmitted() && $treatmentForm->isValid()){
                $treatment = $treatmentForm->getData();

                $entityManager->persist($treatment);
                $entityManager->flush();
                $this->addFlash('treatmentSuccess','Trajtimi u shtua me sukses');
            }
        }

      //if   $treatmentForm->handleRequest()

        return $this->render('user/doc/make_appointment.html.twig',[
            'anamnesisForm'=>$anamnesisForm->createView(),
            'treatmentForm'=>$treatmentForm->createView()
        ]);
    }

    /**
     * @Route("/listAnamnesis",name="app_doc_list_anamnesis")
     */
    public function listAnamneses (AnamnesisRepository $anamnesisRepository, Request $request, PaginatorInterface $paginator){
//        $q = $request->query->get('q');
        $queryBuilder = $anamnesisRepository->getAnamnesesForStaff($this->getUser()->getMedicalStaff()->getId());
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/doc/list_anamneses.html.twig',[
            'pagination'=>$pagination
        ]);
    }


    /**
     * @Route("/addResult",name="app_doc_add_result")
     */
    public function addResult (ClientRepository $clientRepository, PaginatorInterface $paginator, Request $request){

        $queryBuilder = $clientRepository->getALLClients();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('user/doc/list_clients.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/addResultTo/{id}",name="app_doc_add_result_to")
     */
    public function addResultTo (User $user, Request $request,EntityManagerInterface $entityManager){
        $result = new Results();
        $result->setClient($user->getClient());
        $result->setMedicalStaff($this->getUser()->getMedicalStaff());

        $form = $this->createForm(ResultsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $resultsFilename = $form->get('analysisPDF')->getData();
            if ($resultsFilename) {
                $originalFilename = pathinfo($resultsFilename->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $resultsFilename->guessExtension();

                try {
                    $resultsFilename->move(
                        $this->getParameter('doc_results_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                }
                $result->setAnalysisPDF($newFilename);
                $entityManager->persist($result);
                $entityManager->flush();
            }

            $this->addFlash('resultsSuccess','Rezultati u shtua me sukses');

        }
        return $this->render('user/doc/add_result_to.html.twig',[
            'form'=>$form->createView(),
            'user_email'=>$user->getEmail()
        ]);
    }

}