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
        $queryBuilder = $serviceRepository->getWithSearchQueryBuilder($q);
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            8
        );

        return $this->render('listings/servicesList.html.twig',[
            'pagination'=>$pagination
        ]);
    }

}