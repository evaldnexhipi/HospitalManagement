<?php


namespace App\Controller;



use App\Repository\PatientRepository;
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