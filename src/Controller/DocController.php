<?php


namespace App\Controller;
use App\Entity\Anamnesis;
use App\Entity\Reservation;
use App\Entity\Treatment;
use App\Form\AnamnesisFormType;
use App\Form\TreatmentFormType;
use App\Form\UserFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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
}