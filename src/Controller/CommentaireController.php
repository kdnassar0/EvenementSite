<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{

    /**
 * @Route("/evenement/{id}/commentaire", name="evenement_commentaire")
 */
public function Commentaire(Request $request, ManagerRegistry $doctrine, Evenement $evenement)
{
    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    
    if ($form->isSubmitted() && $form->isValid()) {
    

        $commentaire->setEvenement($evenement);
        $commentaire->setUtilisateur($this->getUser());
        $entityManager = $doctrine->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_categorie');

     
    }

    return $this->render('commentaire/index.html.twig', [
        'formAddCommentaire'=>$form->createView()

    ]);
}

} 