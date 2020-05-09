<?php


namespace App\Controller;


use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActionsController extends BaseController
{
    /**
     * @Route("/services",name="app_services")
     */
    public function listServices(ServiceRepository $serviceRepository, Request $request, PaginatorInterface $paginator){
        $q = $request->query->get('q');
        $services = $serviceRepository->findAllWithSearch($q);
//        $pagination = $paginator->paginate(
//            $query,
//            $request->query->getInt('page',1),
//            12
//        );


        return $this->render('listings/servicesList.html.twig',[
            'services'=>$services
        ]);
    }

}