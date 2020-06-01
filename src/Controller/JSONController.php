<?php


namespace App\Controller;



use App\Repository\PatientRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class JSONController extends BaseController
{
    /**
     * @Route("/getPatients", methods={"GET"})
     */
    public function getPatients(PatientRepository $patientRepository){
        $patients = $patientRepository->findAll();
        $results = [];
        foreach ($patients as $patient){
            $results [] = [
              'name'=>$patient->getName(),
              'surname'=>$patient->getSurname()
            ];
        }
        return new JsonResponse($results,201);
    }

    /**
     * @Route("/getLastReservationsFreq",name="admin_get_last_res_freq",methods={"GET"})
     */
    public function getLastReservationsData(ReservationRepository $reservationRepository){
        $month0 = new \DateTime('now');
        $month1 = new \DateTime('-31 days');
        $month2 = new \DateTime('-61 days');
        $month3 = new \DateTime('-92 days');

        $reservationsCount1 = $reservationRepository->getReservationsCountForMonths($month1,$month0);
        $reservationsCount2 = $reservationRepository->getReservationsCountForMonths($month2,$month1);
        $reservationsCount3 = $reservationRepository->getReservationsCountForMonths($month3,$month2);

        $data = [
          ['month'=>$month3->format('m.y').'-'.$month2->format('m.y'),'count'=>$reservationsCount3],
          ['month'=>$month2->format('m.y').'-'.$month1->format('m.y'),'count'=>$reservationsCount2],
          ['month'=>$month1->format('m.y').'-'.$month0->format('m.y'),'count'=>$reservationsCount1]
        ];

        return new JsonResponse($data,200,[
            'Access-Control-Allow-Origin'=>'*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'DNT, X-User-Token, Keep-Alive, User-Agent, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type',
            'Content-Type'=>'application/json'
        ]);
    }

    /**
     * @Route("/getLastReservationsForUser",name="usr_get_last_res_freq",methods={"GET"})
     */
    public function getLastReservationsDataForUser(ReservationRepository $reservationRepository){
        $month0 = new \DateTime('now');
        $month1 = new \DateTime('-31 days');
        $month2 = new \DateTime('-61 days');
        $month3 = new \DateTime('-92 days');

        $reservationsCount1 = $reservationRepository->getReservationsCountForMonthsForUser($this->getUser(),$month1,$month0);
        $reservationsCount2 = $reservationRepository->getReservationsCountForMonthsForUser($this->getUser(),$month2,$month1);
        $reservationsCount3 = $reservationRepository->getReservationsCountForMonthsForUser($this->getUser(),$month3,$month2);

        $data = [
            ['month'=>$month3->format('m.y').'-'.$month2->format('m.y'),'count'=>$reservationsCount3],
            ['month'=>$month2->format('m.y').'-'.$month1->format('m.y'),'count'=>$reservationsCount2],
            ['month'=>$month1->format('m.y').'-'.$month0->format('m.y'),'count'=>$reservationsCount1]
        ];

        return new JsonResponse($data,200,[
            'Access-Control-Allow-Origin'=>'*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'DNT, X-User-Token, Keep-Alive, User-Agent, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type',
            'Content-Type'=>'application/json'
        ]);
    }


    /**
     * @Route("/getDemoData",name="get_demo_data",methods={"GET"})
     */
    public function getDemoData(){
        $demoDatas = [
            ['city'=>'Tirane','population'=>617594],
            ['city'=>'Durres','population'=>181045],
            ['city'=>'Berat','population'=>153060],
            ['city'=>'Sarande','population'=>606519],
            ['city'=>'Shkoder','population'=>105162],
            ['city'=>'Vlore','population'=>95072],
            ['city'=>'Elbasan','population'=>350072]
        ];

        return new JsonResponse($demoDatas,200,[
            'Access-Control-Allow-Origin'=>'*',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'DNT, X-User-Token, Keep-Alive, User-Agent, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type',
            'Content-Type'=>'application/json'
        ]);
    }

    /**
     * @Route("/admin/chartTry")
     */
    public function chartTry(){

        return $this->render('user/admin/chart_try.html.twig');
    }
}