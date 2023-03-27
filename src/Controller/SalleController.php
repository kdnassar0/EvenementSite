<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\LieuRepository;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SalleController extends AbstractController
{
    /**
     * @Route("/salle/{id}/show", name="show_salle")
     * @Route("/salle/edit", name="edit_salle")
     */
    public function index(ManagerRegistry $doctrine,Request $request,Salle $salle): Response
    {
     

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


    /**
     *@Route("/add/salle/{idLieu}" ,name ="add_salle") 
     */

     public function addSalle(LieuRepository $li, Salle $salle=null, ManagerRegistry $doctrine ,Request $request, Lieu $idLieu )


     {

   
      
        $salle = new salle() ;


      //ici on recupere l'id du lieu pour pouvoir ajouter une salle dans ce lieu 
       $salle->setLieu($li->findOneBy(["id"=>$idLieu],[]));
      
      $form = $this->createForm(SalleType::class,$salle) ; 
      $form->handleRequest($request) ;
      

    //   c'est pour que l'utilisateur ne puisee pas saisir un numero negative 
      $capacite =$form["capacite"]->getData();
    
      if($form->isSubmitted() && $form->isValid() && $capacite >=1)
      {
        $entityManager =$doctrine->getManager() ; 
        $entityManager->persist($salle) ; 
        $entityManager->flush() ;
      
        return $this->redirectToRoute('app_lieu') ;
      
      }
      else
      {
       
      }
      return $this->render('salle/add.html.twig',[
        'formAddSalle'=>$form->createView()
      ]);
      

     }



}
