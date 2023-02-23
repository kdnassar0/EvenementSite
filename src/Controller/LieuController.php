<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LieuController extends AbstractController
{


  
  

    /**
     * @Route("/lieu", name="app_lieu")
     * @Route("/add/lieu", name="add_lieu")
     * @Route("/lieu/edit/{id}",name="edit_lieu")
     */
    public function index(LieuRepository $li, ManagerRegistry $doctrine,Lieu $lieu =null ,Request $request ): Response
    {
        $lieus = $li->findBy([],['nom'=>'ASC']) ;
       
        if(!$lieu){
          $lieu = new lieu() ;
        }
        $form=$this->createForm(LieuType::class,$lieu) ; 
        $form->handleRequest($request) ; 

        if($form->isSubmitted() && $form->isValid())
        {
          $lieu=$form->getData()  ;
          $entityManager=$doctrine->getManager();
          $entityManager->persist($lieu);
          $entityManager->flush();
        }

        return $this->render('lieu/index.html.twig', [
          'lieus' => $lieus , 
          'formAddlieu'=>$form->createView()
         
        ]);
    }


    /**
     *@Route("/lieu/{id}/supprimer", name="supprimer_lieu")
     */

    public function supprimerLieu(Lieu $lieu ,ManagerRegistry $doctrine)
    {
      $entityManager =$doctrine->getManager() ;
      $entityManager->remove($lieu) ;
      $entityManager->flush() ;
      
      return $this->redirectToRoute('app_lieu') ;
    }




}
