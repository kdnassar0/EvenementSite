<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Form\LieuType;
use App\Form\SalleType;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
      
        // pour le formulaire d'Edition
        // si le lieu ne existe pas->il va faire un neuveau 
        //si ie lieu existe ->il va modifier 
        if(!$lieu){
          $lieu = new lieu() ;
        }
        else{
          $lieus = [$lieu];
        }


        //on  cree le formulaire, on passe par lieuType ou il y ale formulaire 
        $form=$this->createForm(LieuType::class,$lieu) ; 
        $form->handleRequest($request) ; 

        if($form->isSubmitted() && $form->isValid())
        {

          //si les condition sont juste il va recuperer la data 
          $lieu=$form->getData()  ;
          //on a basoin doctrine pour communiquer avec la base donnees
          $entityManager=$doctrine->getManager();

          $entityManager->persist($lieu);
          $entityManager->flush();
           
          return $this->redirectToRoute('app_lieu') ;
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



    /**
     *@Route("/salle/{id}/supprimer",name="supprimer_salle") 
     */

     public function supprimerSalle(Salle $salle , ManagerRegistry $doctrine)
     {
      $entityManager = $doctrine->getManager() ; 
      $entityManager->remove($salle) ; 
      $entityManager->flush() ;
      return $this->redirectToRoute('app_lieu');
     }




}
