<?php

namespace App\Controller;


use App\Entity\Commentaire;
use App\Entity\Evenement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{


 /**
 * @Route("/commentaire/{id}",name="supprimer_commentaire")
 */

public function supprimerCommentaire(ManagerRegistry $doctrine,Commentaire $commentaire,$id)
  {

  
  //ici on  va recuperer l'id d'un evenement pour pouvoir rediriger l'utilisateur vers la page detail evenement   
  $evenementId = $commentaire->getEvenement()->getId();


   $entityManager=$doctrine->getManager();
   $entityManager->remove($commentaire) ; 
   $entityManager->flush();

 
  
    

   return $this->redirectToRoute('details_evenement',['id'=>$evenementId
]);

}

} 