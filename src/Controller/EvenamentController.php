<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\User;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvenamentController extends AbstractController
{
    /**
     * @Route("/evenament", name="app_evenament")
     */
    public function index(EvenementRepository $e): Response
    {

      $evenementsPassees = $e-> findEvenementsPassees() ;
      $evenementsEncours = $e-> findEvenementsEncours() ;

   
     
     
    

        return $this->render('evenament/index.html.twig', [
          'evenementsEncours' => $evenementsEncours, 
          'evenementsPassees'=>$evenementsPassees, 
           

        ]);
    }

    /**
     *@Route("/evenement/{id}/suprimmer",name="supprimer_evenement")
     */

     public function supprimerEvenement(Evenement $evenement , ManagerRegistry $doctrine)
     {

        $entityManager =$doctrine->getManager() ; 
        $entityManager->remove($evenement) ; 
        $entityManager->flush() ;

        return $this->redirectToRoute('app_evenament') ;


     }


}
