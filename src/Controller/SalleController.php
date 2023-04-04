<?php

namespace App\Controller;

use App\Entity\Evenement;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class SalleController extends AbstractController
{

  /**
   * @Route("/salle/{id}/show", name="show_salle")
   */
  public function index(Salle $salle): Response
  {
    return $this->render('salle/show.html.twig', [
      "infoSalle" => $salle
    ]);
  }




  
  /**
   * @Route("/salle/{idLieu}/add", name="add_salle")
   * @ParamConverter("lieu", class="App\Entity\Lieu", options={"id" = "idLieu"})
   */
  public function addSalle(Request $request, Lieu $lieu, ManagerRegistry $doctrine, SluggerInterface $slugger,Salle $salle)
  {
  
        $salle = new Salle();
    
   

      $form = $this->createForm(SalleType::class, $salle);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $file = $form->get('image')->getData();
          if ($file) {
              $orginalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
  
              $safeFileName = $slugger->slug($orginalFilename);
              $newFilename = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();
              $salle->setImage($newFilename);
  
              $entityManager = $doctrine->getManager();
              $salle->setLieu($lieu); // assigner la salle au lieu correspondant
              $entityManager->persist($salle);
              $entityManager->flush();
  
              try {
                  $file->move(
                      $this->getParameter('salle_directory'),
                      $newFilename
                  );
              } catch (FileException $e) {
              }
  
              return $this->redirectToRoute('app_lieu');
          }
      }
  
      return $this->render('salle/add.html.twig', [
          'formAddSalle' => $form->createView()
      ]);
  }
  
}
