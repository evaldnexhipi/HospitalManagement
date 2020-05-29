<?php


namespace App\Controller;
use App\Entity\Departament;
use App\Entity\Hall;
use App\Entity\MedicalStaff;
use App\Entity\Patient;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\Service;
use App\Entity\User;
use App\Form\AprovoRezervimFormType;
use App\Form\DepartamentFormType;
use App\Form\HallFormType;
use App\Form\PatientFormType;
use App\Form\RegisterDocFormType;
use App\Form\RegistrationFormType;
use App\Form\RoomFormType;
use App\Form\ServiceFormType;
use App\Form\UserFormType;
use App\Repository\DepartamentRepository;
use App\Repository\HallRepository;
use App\Repository\MedicalStaffRepository;
use App\Repository\PatientRepository;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\ServiceRepository;
use App\Repository\SpecialityRepository;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/manage",name="app_admin_profile_manage")
     */
    public function manageProfile(Request $request, EntityManagerInterface $entityManager) {
    $user = $this->getUser();
    $form = $this->createForm(UserFormType::class,$user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

        $entityManager->persist($user);
        $entityManager->flush();
    }

    return $this->render('user/admin/manage_profile.html.twig',[
        'userForm'=>$form->createView()
    ]);
}

    /**
     * @Route("/reservationssoon",name="app_admin_reservations_soon")
     */
    public function listReservationsSoon(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){
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
     * @Route("/reservationsdone",name="app_admin_reservations_done")
     */
    public function listDoneReservations(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator){
        $queryBuilder = $reservationRepository->getAllDoneReservations();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/admin/rezervimet_done.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/reservationssoon/approve/{id}",name="app_admin_reservations_soon_approve")
     */
    public function approveReservation(Reservation $reservation, Request $request, EntityManagerInterface $entityManager){
        $cost = $reservation->getService()->getCost();
        $form = $this->createForm(AprovoRezervimFormType::class,$reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $reservation->setStatus('paguar');

            $this->addFlash('aprovoSuccess','Rezervimi u aprovua me sukses');
            $entityManager->flush();
        }

        return $this->render('user/admin/aprovo_rezervim.html.twig',[
            'aprovoForm'=>$form->createView(),
            'cost'=>$cost
        ]);
    }

    /**
     * @Route("/listStaff",name="app_admin_list_staff")
     */
    public function listStaff(MedicalStaffRepository $medicalStaffRepository, Request $request, PaginatorInterface $paginator){
        $q = $request->query->get('q');
        $queryBuilder = $medicalStaffRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('user/admin/list_staff.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/addStaff",name="app_admin_add_staff")
     */
    public function addStaff(Request $request,TokenGenerator $tokenGenerator, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder){
        $regForm = $this->createForm(RegistrationFormType::class);
        $docForm = $this->createForm(RegisterDocFormType::class);

        $regForm->handleRequest($request);
        $docForm->handleRequest($request);

        if($regForm->isSubmitted() && $regForm->isValid()){
            $user = new User();
            $staff = new MedicalStaff();
            $user = $regForm->getData();
            $token = $tokenGenerator->generateToken();
            $user->setToken($token);
            $user->setIsActive(true);

            $user->setFirstName(ucfirst(strtolower($regForm->get('firstName')->getData())));
            $user->setLastName(ucfirst(strtolower($regForm->get('lastName')->getData())));
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setEmail($regForm->get('email')->getData());
            $user->setGender($regForm->get('gender')->getData());
            $user->setBirthday($regForm->get('birthday')->getData());
            if (!is_numeric($regForm->get('telephone')->getData())) {
                $regForm->addError(new FormError('Telefoni te permbaje numra', 'telephone', 'telephone', 'telephone'));
            }
            $teli = $regForm->get('telephone')->getData();
            $user->setTelephone($teli);
            $user->setAddress($regForm->get('address')->getData());

            $imageFile = $regForm->get('imageFilename')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('user_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                }
                $user->setImageFilename($newFilename);
            }

            $staff->setHall($docForm->get('hall')->getData());
            $staff->setSpeciality($docForm->get('speciality')->getData());
            $staff->setStatus(true);

            $user->setMedicalStaff($staff);
            $staff->setUser($user);
            $user->addRole('ROLE_DOC');

            $entityManager->persist($user);
            $entityManager->persist($staff);
            $entityManager->flush();

            $this->addFlash('staffRegister','Regjistrim me sukses i stafit');
        }

        return $this->render('user/admin/add_staff.html.twig',[
            'regForm'=>$regForm->createView(),
            'docForm'=>$docForm->createView()
        ]);
    }

    /**
     * @Route("/removeStaff/{id}",name="app_admin_remove_staff")
     */
    public function removeStaff(User $user, EntityManagerInterface $entityManager, Filesystem $filesystem){
        $filesystem->remove($this->getParameter('user_images_directory').'/'.$user->getImageFilename());
        $entityManager->remove($user->getMedicalStaff());
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('deleteSuccess','Personeli u hoq me sukses');
        return $this->redirectToRoute("app_admin_list_staff");
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @Route("/addDepartament",name="app_admin_add_department")
     */
    public function addDepartament(EntityManagerInterface $entityManager, Request $request){
        $dep = new Departament();
        $form = $this->createForm(DepartamentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $dep->setName($form->get('name')->getData());
            $dep->setDescription($form->get('description')->getData());

            $entityManager->persist($dep);
            $entityManager->flush();
            $this->addFlash('departamentSuccess','Departamenti u shtua me sukses');
        }

        return $this->render('user/admin/add_departament.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/updateDepartament/{id}",name="app_admin_update_dep")
     */
    public function updateDepartament(Departament $departament, EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(DepartamentFormType::class,$departament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $departament->setName($form->get('name')->getData());
            $departament->setDescription($form->get('description')->getData());

            $entityManager->flush();
            $this->addFlash('departamentChange','Departamenti u modifikua me sukses');
        }

        return $this->render('user/admin/manage_departament.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/deleteDepartament/{id}",name="app_admin_delete_dep")
     */
    public function deleteDepartament(Departament $departament, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($departament);
        $entityManager->flush();
        $this->addFlash('deleteDepSuccess','Departamenti u fshi me sukses');
        return $this->redirectToRoute('app_admin_list_deps');
    }


    /**
     * @Route("/listDepartments",name="app_admin_list_deps")
     */
    public function listDepartaments (DepartamentRepository $departamentRepository, Request $request, PaginatorInterface $paginator){
        $queryBuilder = $departamentRepository->getAllDepartamentsWithQuery();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );


        return $this->render('user/admin/list_deps.html.twig',[
           'pagination'=>$pagination
//            'deps'=>[]
        ]);
    }

    /**
     * @Route("/listServices",name="app_admin_list_services")
     */
    public function listSerivces (ServiceRepository $serviceRepository, Request $request, PaginatorInterface $paginator){
        $q = $request->query->get('q');
        $queryBuilder = $serviceRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('user/admin/list_services.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/addService",name="app_admin_add_service")
     */
    public function addService (EntityManagerInterface $entityManager, Request $request){
        $service = new Service();
        $form = $this->createForm(ServiceFormType::class,$service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($service);
            $entityManager->flush();
            $this->addFlash('serviceSuccess','Sherbimi u shtua me sukses');
        }
        return $this->render('user/admin/add_service.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/updateService/{id}",name="app_admin_update_service")
     */
    public function updateService (Service $service, Request $request, EntityManagerInterface $entityManager){
        $form = $this->createForm(ServiceFormType::class,$service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('serviceUpdateSuccess','Sherbimi u modifikua me sukses');
        }
        return $this->render('user/admin/manage_service.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/deleteService/{id}",name="app_admin_delete_service")
     */
    public function deleteService(Service $service, EntityManagerInterface $entityManager){
        $entityManager->remove($service);
        $entityManager->flush();
        $this->addFlash('serviceDeleteSucess','Sherbimi u fshi me sukses');
        return $this->redirectToRoute('app_admin_list_services');
    }

    /**
     * @Route("/listSpecialities",name="app_admin_list_specialities")
     */
    public function listSpecialities(SpecialityRepository $specialityRepository, Request $request, PaginatorInterface $paginator){
        $q = $request->query->get('q');
        $queryBuilder = $specialityRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('user/admin/list_specialities.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/listRooms",name="app_admin_list_rooms")
     */
    public function listRooms(RoomRepository $roomRepository,Request $request, PaginatorInterface $paginator){
        $q =  $request->query->get('q');
        $queryBuilder = $roomRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('user/admin/list_rooms.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/addRoom",name="app_admin_add_room")
     */
    public function addRoom(EntityManagerInterface $entityManager,Request $request){
        $room = new Room();
        $form = $this->createForm(RoomFormType::class,$room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($room);
            $entityManager->flush();
            $this->addFlash('roomSuccess','Dhoma u shtua me sukses');
        }

        return $this->render('user/admin/add_room.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/updateRoom/{id}",name="app_admin_update_room")
     */
    public function updateRoom (Room $room,EntityManagerInterface $entityManager,Request $request){
        $form = $this->createForm(RoomFormType::class,$room);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('roomUpdateSuccess','Dhoma u modifikua me sukses');
        }

        return $this->render('user/admin/manage_room.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/deleteRoom/{id}",name="app_admin_delete_room")
     */
    public function deleteRoom (Room $room,EntityManagerInterface $entityManager){
        $entityManager->remove($room);
        $entityManager->flush();
        $this->addFlash('roomDeleteSuccess','Dhoma u fshi me sukses');
        return $this->redirectToRoute('app_admin_list_rooms');
    }

    /**
     * @Route("/listHalls",name="app_admin_list_halls")
     */
    public function listHalls(HallRepository $hallRepository,Request $request, PaginatorInterface $paginator){
        $q =  $request->query->get('q');
        $queryBuilder = $hallRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('user/admin/list_halls.html.twig',[
            'pagination'=>$pagination
        ]);
    }

    /**
     * @Route("/addHall",name="app_admin_add_hall")
     */
    public function addHall(EntityManagerInterface $entityManager,Request $request){
        $hall = new Hall();
        $form = $this->createForm(HallFormType::class,$hall);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($hall);
            $entityManager->flush();
            $this->addFlash('hallSuccess','Salla u shtua me sukses');
        }

        return $this->render('user/admin/add_hall.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/updateHall/{id}",name="app_admin_update_hall")
     */
    public function updateHall (Hall $hall,EntityManagerInterface $entityManager,Request $request){
        $form = $this->createForm(HallFormType::class,$hall);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('hallUpdateSuccess','Salla u modifikua me sukses');
        }

        return $this->render('user/admin/manage_hall.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/deleteHall/{id}",name="app_admin_delete_hall")
     */
    public function deleteHall (Hall $hall, EntityManagerInterface $entityManager){
        $entityManager->remove($hall);
        $entityManager->flush();
        $this->addFlash('hallDeleteSuccess','Salla u fshi me sukses');
        return $this->redirectToRoute('app_admin_list_halls');
    }

    /**
     * @Route("/listPatients",name="app_admin_list_patients")
     */
    public function listPatients(PatientRepository $patientRepository, Request $request, PaginatorInterface $paginator){
        $q =  $request->query->get('q');
        $queryBuilder = $patientRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('user/admin/list_patients.html.twig',[
            'pagination'=>$pagination
        ]);
    }


    /**
     * @Route("/addPatient",name="app_admin_add_patient")
     */
    public function addPatient(EntityManagerInterface $entityManager, Request $request){
        $patient = new Patient();
        $form = $this->createForm(PatientFormType::class,$patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($patient);
            $entityManager->flush();
            $this->addFlash('patientSuccess','Pacienti u regjistrua');
        }

        return $this->render('user/admin/add_patient.html.twig',[
            'userForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/leavePatient/{id}",name="app_admin_leave_patient")
     */
    public function leavePatient(){

    }

}