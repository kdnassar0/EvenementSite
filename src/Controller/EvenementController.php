<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\SalleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EvenementController extends AbstractController
{




     /**
     * @Route("/categorie/{id}", name="evenement_categorie")
     */
    public function evenementsParCategorie(EvenementRepository $e, Categorie $categorie): Response
    {
        
        $evenementsPassees = $e->findEvenementsPasseesParCategorie($categorie->getId());
        $evenementsEncours = $e->findEvenementsEncoursParCategorie($categorie->getId());

        return $this->render('evenement/index.html.twig', [
            'evenementsPassees' => $evenementsPassees,
            'evenementsEncours'=>$evenementsEncours
        ]);
    }

    



  /**
   * @Route("/evenement/add",name="add_evenement")
   */

  public function add(Evenement $evenement = null, Request $requeste, ManagerRegistry $doctrine, SluggerInterface $slugger,SalleRepository $sa ): Response
  {


      $salles = $sa->findAll([],['numero'=>'ASC']) ;
   

    $form = $this->createForm(EvenementType::class, $evenement);
    // Transmission des salles au formulaire
    $form->get('salles')->setData($salles);
    $form->handleRequest($requeste);
 

    if ($form->isSubmitted() && $form->isValid()) {
      $file = $form->get('image')->getData();
      if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $evenement = $form->getData(); 
        $evenement->setImage($newFilename);
     
     
     

        $evenement->setCreateur($this->getUser());
        $evenement->setStatut('en attente') ;
        $entityManager = $doctrine->getManager();
        $entityManager->persist($evenement);
        $entityManager->flush();
        dump($form);



        try {
          $file->move(
            $this->getParameter('evenement_directory'),
            $newFilename
          );
        } catch (FileException $e) {
        }

        return $this->redirectToRoute('app_categorie');
      }
    }



    return $this->render('evenement/add.html.twig', [

      'formAddEvenement' => $form->createView()



    ]);
   

  }

  /**
   *@Route("/evenement/{id}/suprimmer",name="supprimer_evenement")
   */

  public function supprimerEvenement(Evenement $evenement, ManagerRegistry $doctrine)
  {

    $categorieId =$evenement->getCategorie()->getId() ;

    $entityManager = $doctrine->getManager();
    $entityManager->remove($evenement);
    $entityManager->flush();

    return $this->redirectToRoute('evenement_categorie',['id'=>$categorieId]);
  }


  /**
   * @Route("evenement/{id}/add/",name="add_participant")
   */

  public function addParticipant(Evenement $evenement, ManagerRegistry $doctrine)
  {
    
    $categorieId =$evenement->getCategorie()->getId() ;
    dd($categorieId);
    $entityManager = $doctrine->getManager();
    $evenement->addParticipant($this->getUser());
    $entityManager->flush();


    return $this->redirectToRoute('evenement_categorie',['id'=>$categorieId]);
  }



}
