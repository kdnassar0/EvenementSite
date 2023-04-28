<?php

namespace App\Controller;

use DateTime;
use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\SalleRepository;
use Symfony\Component\Form\FormError;
use App\Repository\CategorieRepository;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
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
      'evenementsEncours' => $evenementsEncours
    ]);
  }


  
  /**
   * @Route("/evenement/add",name="add_evenement")
   */

  public function add(Evenement $evenement = null, Request $requeste, ManagerRegistry $doctrine, SluggerInterface $slugger, SalleRepository $sa ): Response
  {


    $salles = $sa->findAll([], ['numero' => 'ASC']);
  
  

    $form = $this->createForm(EvenementType::class, $evenement);
    // Transmission des salles au formulaire
    $form->get('salles')->setData($salles);
  
    $form->handleRequest($requeste);

{
    if ($form->isSubmitted() && $form->isValid()) {
     
      $file = $form->get('image')->getData();
      $dateDebut = $form->get('date_debut')->getData();
      $dateAujourdhui = new \DateTime();
      if($dateDebut < $dateAujourdhui){
        $form->get('date_debut')->addError(new FormError('La date de début ne peut pas être antérieure à aujourd\'hui.'));
      }else{
      if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $evenement = $form->getData();
        $evenement->setImage($newFilename);

      

        $evenement->setCreateur($this->getUser());
        $evenement->setStatut('en attente');
        $entityManager = $doctrine->getManager();
        $entityManager->persist($evenement);
        $entityManager->flush();

        }

      

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
    }

  

    return $this->render('evenement/add.html.twig', [

      'formAddEvenement' => $form->createView()
    ]);
  }






  /**
   *@Route("/evenement/{id}/suprimmer",name="supprimer_evenement")
   */

  public function supprimerEvenement(Evenement $evenement, ManagerRegistry $doctrine, Filesystem $filesystem)
  {

    $entityManager = $doctrine->getManager();
    $entityManager->remove($evenement);
    // Récupérer le chemin du fichier image de la salle à supprimer
    $imagePath = $this->getParameter('evenement_directory') . '/' . $evenement->getImage();
    $filesystem->remove($imagePath);
    $entityManager->flush();

    return $this->redirectToRoute('app_categorie');
  }


  /**
   * @Route("evenement/{id}/add/",name="add_participant")
   */

  public function addParticipant(Evenement $evenement, ManagerRegistry $doctrine)
  {

    $entityManager = $doctrine->getManager();
    $evenement->addParticipant($this->getUser());
    $entityManager->flush();


    return $this->redirectToRoute('app_categorie');
  }
}
