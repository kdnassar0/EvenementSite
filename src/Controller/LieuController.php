<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Form\LieuType;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use App\Repository\LieuRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;


class LieuController extends AbstractController
{




  /**
   * @Route("/lieu", name="app_lieu")
   * @Route("/add/lieu", name="add_lieu")
   * @Route("/lieu/edit/{id}",name="edit_lieu")
   */
  public function index(LieuRepository $li, ManagerRegistry $doctrine, Lieu $lieu = null, Request $request, SluggerInterface $slugger, Security $security,EvenementRepository $ev ): Response
  {
    $lieus = $li->findBy([], ['nom' => 'ASC']);
    $evenemntsPassees=$ev->findEvenementsPassees();

    // pour le formulaire d'Edition
    // si le lieu ne existe pas->il va faire un neuveau 
    //si ie lieu existe ->il va modifier 
    $isAdmin = $security->isGranted('ROLE_ADMIN');

    if ($isAdmin) {

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
        $imageSalle = $form->get('imageSalle')->getData();
        if ($file) {
          $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
          // this is needed to safely include the file name as part of the URL
          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
          //si les condition sont juste il va recuperer la data 
          $lieu = $form->getData();
          $lieu->setImage($newFilename);

      
          try {
            $file->move(
              $this->getParameter('lieu_directory'),
              $newFilename
            );
          } catch (FileException $e) {
          }
        }
        if ($imageSalle) {
          $originalFilename = pathinfo($imageSalle->getClientOriginalName(), PATHINFO_FILENAME);
          // this is needed to safely include the file name as part of the URL
          $safeFilename = $slugger->slug($originalFilename);
          $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageSalle->guessExtension();
          //si les condition sont juste il va recuperer la data 
          $lieu = $form->getData();
          $lieu->setImageSalle($newFilename);

          //on a basoin doctrine pour communiquer avec la base donnees

         
          try {
            $imageSalle->move(
              $this->getParameter('lieu_directory'),
              $newFilename
            );
          } catch (FileException $e) {
          }
        }
          $entityManager = $doctrine->getManager();
          $entityManager->persist($lieu);
          $entityManager->flush();


          return $this->redirectToRoute('app_lieu');
        
      }





      return $this->render('lieu/index.html.twig', [
        'lieus' => $lieus,
        'formAddlieu' => $form->createView(),
        'evenemntsPassees'=>$evenemntsPassees

      ]);
    }
    return $this->render('lieu/index.html.twig', [
      'lieus' => $lieus,
      'formAddlieu' => null,
      'evenemntsPassees'=>$evenemntsPassees

    ]);
  }

  /**
   *@Route("/lieu/{id}/supprimer", name="supprimer_lieu")
   */

  public function supprimerLieu(Lieu $lieu = null, ManagerRegistry $doctrine, Filesystem $filesystem, Security $security)
  {
    $isAdmin = $security->isGranted('ROLE_ADMIN');

    if ($isAdmin) {
      if ($lieu) {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($lieu);
        // Récupérer le chemin du fichier image de la salle à supprimer
        $imagePath = $this->getParameter('lieu_directory') . '/' . $lieu->getImage();
        $imagePath = $this->getParameter('lieu_directory') . '/' . $lieu->getImageSalle();

        // Supprimer le fichier image du lieu
        $filesystem->remove($imagePath);

        $entityManager->flush();

        return $this->redirectToRoute('app_lieu');
      }

      return $this->redirectToRoute('app_categorie');
    }
    return $this->redirectToRoute('app_categorie');
  }
}
