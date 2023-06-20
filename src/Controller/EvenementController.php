<?php

namespace App\Controller;


use App\Entity\Lieu;
use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EvenementController extends AbstractController
{




  /**
   * @Route("/categorie/{id}", name="evenement_categorie")
   */
  public function evenementsParCategorie(EvenementRepository $e, Categorie $categorie): Response
  {

   
    $evenementsEncours = $e->findEvenementsEncoursParCategorie($categorie->getId());


 


    return $this->render('evenement/index.html.twig', [
      
      'evenementsEncours' => $evenementsEncours
    
    ]);
  }



  /**
   * @Route("/evenement/add",name="add_evenement")
   * @Route("/evenement/{id}/Edit",name ="edit_evenement")
   */

  public function add(Evenement $evenement = null, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
  {

    // Vérifier si l'utilisateur est connecté
    if (!$this->getUser()) {
      $this->addFlash('warning', ' Veuillez vous connecter pour ajouter un événement.');
      return $this->redirectToRoute('app_login');
    }

    $lieu = $doctrine->getRepository(Lieu::class)->findAll();
    $lieu[0]->getCapacity();

    if (!$evenement) {
      $evenement = new Evenement();
  } else {
      $evenement = $evenement;
  }

    $form = $this->createForm(EvenementType::class, $evenement);
    $form->handleRequest($request); 
      if ($form->isSubmitted() && $form->isValid()) {

        $dateDebut = $form->get('date_debut')->getData('Y-m-d H:m');
        $datefin = $form->get('date_fin')->getData('Y-m-d H:m');
        
       
        if($dateDebut == $datefin OR $dateDebut > $datefin){
      
          $form->get('date_debut')->addError(new FormError('vous ne pouvez pas ajouter un evenement '));
          }else{
          
          // Vérifier si un événement existe déjà à la même date
          $existingEvent = $doctrine->getRepository(Evenement::class)->findOneBy(['date_debut' => $dateDebut]);
          
          if ($existingEvent) {
            $form->get('date_debut')->addError(new FormError('Un événement existe déjà à cette date.'));
          } else {
            // Le code pour créer et enregistrer l'événement ici
            
            $file = $form->get('image')->getData();
            $imageAffiche = $form->get('imageAffiche')->getData();
            
            $dateDebut = $form->get('date_debut')->getData();
            $dateAujourdhui = new \DateTime('Europe/Paris');
            
            $nbDesPlaces = $form->get('nb_des_places')->getData();
            if ($dateDebut < $dateAujourdhui) {
              $form->get('date_debut')->addError(new FormError('La date de début ne peut pas être antérieure à aujourd\'hui.'));
            }elseif($nbDesPlaces > $lieu[0]->getCapacity()){
              $form->get('nb_des_places')->addError(new FormError('Le nombre de places est supérieur à la capacité de notre lieu.'));
            }
            else {
              if ($imageAffiche) {
              $originalFilename = pathinfo($imageAffiche->getClientOriginalName(), PATHINFO_FILENAME);
              // this is needed to safely include the file name as part of the URL
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageAffiche->guessExtension();
              //si les condition sont juste il va recuperer la data 
              $evenement = $form->getData();
              $evenement->setImageAffiche($newFilename);
              //on a basoin doctrine pour communiquer avec la base donnees
              try {
                $imageAffiche->move(
                  $this->getParameter('evenement_directory'),
                  $newFilename
                );
              } catch (FileException $e) {
              }
            }
            if ($file) {
              $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
              // this is needed to safely include the file name as part of the URL
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
              $evenement = $form->getData();
              $evenement->setImage($newFilename);-
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
      }
    


    return $this->render('evenement/add.html.twig', [

      'formAddEvenement' => $form->createView()
    ]);
  }






  /**
   *@Route("/evenement/{id}/supprimer",name="supprimer_evenement")
   */

  public function supprimerEvenement(Evenement $evenement = null, ManagerRegistry $doctrine, Filesystem $filesystem,Security $security)
  {
    // Vérifier si l'utilisateur actuel est l'auteur de l'événement

    if ($evenement) {
      $isAdmin = $security->isGranted('ROLE_ADMIN');
      if ($evenement->getCreateur() == $this->getUser() or  $isAdmin  ) {
        $categorie = $evenement->getCategorie();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($evenement);
        // Récupérer le chemin du fichier image de l'evenement à supprimer
        $imagePath = $this->getParameter('evenement_directory') . '/' . $evenement->getImage();
        $filesystem->remove($imagePath);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin'
      ) ;
      }
    }
    return $this->redirectToRoute('app_categorie');
  }


  /**
   * @Route("/evenement/{id}/addParticipant",name="add_participant")
   */

  public function addParticipant(Evenement $evenement = null, ManagerRegistry $doctrine)
  {
    if (!$this->getUser()) {
      $this->addFlash('warning', ' Veuillez vous connecter pour participer cet evenement.');
      return $this->redirectToRoute('app_login');
    }
    if ($evenement) {
      
      $entityManager = $doctrine->getManager();
      $evenement->addParticipant($this->getUser());
      $entityManager->flush();


      return $this->redirectToRoute('details_evenement', [
        'idEvent' => $evenement->getId()
    ]) ;
    }
    return $this->redirectToRoute('app_categorie');
  }

  /**
   * @Route("/evenement/{id}/supprimerParticipant/{idParticipant}",name="supprimer_participant")
   */

  public function supprimerParticipant(Evenement $evenement = null, ManagerRegistry $doctrine,$idParticipant)
  {
   
    if (!$this->getUser()) {
      $this->addFlash('warning', ' Veuillez vous connecter pour participer cet événement .');
      return $this->redirectToRoute('app_login');
    }
    if ($evenement) {
      $userRepository = $doctrine->getRepository(User::class);
      $participant = $userRepository->find($idParticipant);
      $entityManager = $doctrine->getManager();
      $evenement->removeParticipant($participant);
      $entityManager->flush();


      return $this->redirectToRoute('details_evenement', [
        'idEvent' => $evenement->getId()
    ]) ;
    
    return $this->redirectToRoute('app_categorie');
  }

return $this->redirectToRoute('app_categorie');
  }
  
}
