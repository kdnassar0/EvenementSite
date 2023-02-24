<?php

namespace App\Controller;

use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Salle;
use App\Form\SalleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class SalleController extends AbstractController
{
    /**
     * @Route("/salle/{id}/show", name="show_salle")
     * @Route("/salle/edit", name="edit_salle")
     */
    public function index(SalleRepository $sa ,Salle $id,ManagerRegistry $doctrine,Request $request,Salle $salle=null): Response
    {
     

        $salle=$sa->findOneBy(["id"=>$id],["numero"=>"ASC"]) ;
        
        
        $form = $this->createForm(SalleType::class,$salle) ;
        $form->handleRequest($request) ;

        if($form->isSubmitted() && $form->isValid())
        {
            $salle = $form->getData() ;
            $entityManager= $doctrine->getManager() ; 
            $entityManager->persist($salle) ; 
            $entityManager->flush() ; 

            return $this->redirectToRoute('app_lieu') ;


        }

        return $this->render('salle/show.html.twig', [
           "infoSalle"=>$salle ,
           "formEditSalle"=>$form->createView() 
        ]);
    }
}
