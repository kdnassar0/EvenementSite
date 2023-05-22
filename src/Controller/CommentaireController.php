<?php

namespace App\Controller;


use App\Entity\Evenement;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentaireController extends AbstractController
{


/**
 * @Route("/commentaire/{id}", name="supprimer_commentaire")
 */
public function supprimerCommentaire(ManagerRegistry $doctrine, Commentaire $commentaire,SessionInterface $session)
{
 
  

    // Récupérer l'ID de l'événement pour rediriger l'utilisateur vers la page de détails de l'événement
    $evenementId = $commentaire->getEvenement()->getId();

    $entityManager = $doctrine->getManager();
    $entityManager->remove($commentaire);
    $entityManager->flush();

    $session->getFlashBag()->add('success', 'Le commentaire a été supprimé avec succès.');

    return $this->redirectToRoute('details_evenement', ['id' => $evenementId]);
}


} 