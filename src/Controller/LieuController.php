<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class LieuController extends AbstractController
{




  /**
   * @Route("/lieu", name="app_lieu")
   * @Route("/add/lieu", name="add_lieu")
   * @Route("/lieu/edit/{id}",name="edit_lieu")
   */
  public function index(LieuRepository $li, ManagerRegistry $doctrine, Lieu $lieu = null, Request $request, SluggerInterface $slugger): Response
  {
    $lieus = $li->findBy([], ['nom' => 'ASC']);

    // pour le formulaire d'Edition
    // si le lieu ne existe pas->il va faire un neuveau 
    //si ie lieu existe ->il va modifier 
    if (!$lieu) {
      $lieu = new lieu();
    } else {
      $lieus = [$lieu];
    }


    //on  cree le formulaire, on passe par lieuType ou il y ale formulaire 
    $form = $this->createForm(LieuType::class, $lieu);
    $form->handleRequest($request);



    if ($form->isSubmitted() && $form->isValid()) {

      $file = $form->get('image')->getData();
      if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        //si les condition sont juste il va recuperer la data 
       
        $lieu = $form->getData();
        $lieu->setImage($newFilename);



        //on a basoin doctrine pour communiquer avec la base donnees

        $entityManager = $doctrine->getManager();
        $entityManager->persist($lieu);
        $entityManager->flush();



        try {
          $file->move(
            $this->getParameter('lieu_directory'),
            $newFilename
          );
        } catch (FileException $e) {
        }


        return $this->redirectToRoute('app_lieu');
      }
    }

    // dd($form);


    return $this->render('lieu/index.html.twig', [
      'lieus' => $lieus,
      'formAddlieu' => $form->createView()

    ]);
  }

  /**
   *@Route("/lieu/{id}/supprimer", name="supprimer_lieu")
   */

  public function supprimerLieu(Lieu $lieu, ManagerRegistry $doctrine,Filesystem $filesystem)
  {
    $entityManager = $doctrine->getManager();
    $entityManager->remove($lieu);
        // Récupérer le chemin du fichier image de la salle à supprimer
        $imagePath = $this->getParameter('lieu_directory') . '/' . $lieu->getImage();

        // Supprimer le fichier image du lieu
        $filesystem->remove($imagePath);


    $entityManager->flush();

    return $this->redirectToRoute('app_lieu');
  }



  /**
   *@Route("/salle/{id}/supprimer",name="supprimer_salle") 
   */

  public function supprimerSalle(Salle $salle, ManagerRegistry $doctrine,Filesystem $filesystem)
  {
    $entityManager = $doctrine->getManager();
    $entityManager->remove($salle);
    
    // Récupérer le chemin du fichier image de la salle à supprimer
    $imagePath = $this->getParameter('salle_directory') . '/' . $salle->getImage();

    // Supprimer le fichier image de la salle
    $filesystem->remove($imagePath);
    $entityManager->flush();
    return $this->redirectToRoute('app_lieu');
  }
}
