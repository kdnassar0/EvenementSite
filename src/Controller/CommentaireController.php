<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Evenement;

use App\Entity\Commentaire;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class CommentaireController extends AbstractController
{


    /**
     * @Route("/commentaire/{id}", name="supprimer_commentaire")
     */
    public function supprimerCommentaire(ManagerRegistry $doctrine, Commentaire $commentaire = null)
    {
        if ($commentaire) {
            if ($commentaire->getUtilisateur() == $this->getUser()) {

                // Récupérer l'ID de l'événement pour rediriger l'utilisateur vers la page de détails de l'événement
                $evenementId = $commentaire->getEvenement()->getId();
                $entityManager = $doctrine->getManager();
                $entityManager->remove($commentaire);
                $entityManager->flush();

                $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');
                return $this->redirectToRoute('details_evenement', ['id' => $evenementId]);
            }
        }
        $this->addFlash(
            'test',
            "l'url que vous venez de siasir ne fonctionne pas"
        );
        return $this->redirectToRoute('app_categorie');
    }
}
