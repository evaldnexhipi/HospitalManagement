<?php


namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController
{

    /**
     * @Route("/",name="homepage")
     */
    public function homepage(){
        return $this->render('Home/homepage.html.twig');
    }

    /**
     * @Route("/departament/{number}",name="admin_profile")
     */
    public function getDepartament($number, EntityManagerInterface $em){
        $dep = $em->getRepository('App:Departament')->findOneBy(['name'=>'Departamenti_'.$number]);
        $arr = [];
        $arr [] = ['name' => $dep->getName()];
        $arr [] = ['description' => $dep->getDescription()];
        $arr [] = ['createdAt' => $dep->getCreatedAt()];
        $arr [] = ['updatedAt' => $dep->getUpdatedAt()];

        return new JsonResponse($arr,200);
    }

    /**
     * @Route("/product/upload",name="upload_product")
     */
    public function uploadFile(Request $request, EntityManagerInterface $em){
        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochureFilename')->getData();

            if($brochureFile){
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(),PATHINFO_FILENAME);
                //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = /*$safeFilename.'-'.*/uniqid().'.'.$brochureFile->guessExtension();

                try{
                    $brochureFile->move(
                      $this->getParameter('brochures_directory'),
                      $newFilename
                    );
                }
                catch(FileException $e){
                    dd($e);
                }
                $product->setBrochureFilename($newFilename);
                $em->persist($product);
                $em->flush();
            }
           // return $this->redirect($this->generateUrl('homepage'));
        }
        return $this->render('product/new.html.twig',[
           'form' => $form->createView(),
            'product' => $product,
        ]);
    }


}