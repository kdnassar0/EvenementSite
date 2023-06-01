<?php

namespace App\Controller;


use App\Entity\Evenement;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\EvenementRepository;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{


    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {


        return $this->render('user/index.html.twig', []);
    }



    /**
     * @Route("/details/{idEvent}", name="details_evenement")
     * @Route("/edit/commentaire/{idEvent}/{idCommentaire}", name="edit_commentaire")
     * @ParamConverter("evenement", options={"mapping": {"idEvent" : "id"}})
     * @ParamConverter("commentaire",options={"mapping":{"idCommentaire" : "id"}})
     */

    public function detailsEvenement(
        ManagerRegistry $doctrine,
        Evenement $evenement,
        Request $request,
        Commentaire $commentaire =null,
        CommentaireRepository $co
    ) {


  
          
            if (!$commentaire) {
                $commentaire = new Commentaire();
            } else {
                $commentaires = $commentaire;
            }
        
          
        




        $createur = $evenement->getCreateur();
        $commentaires = $evenement->getCommentaires();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            if (!$this->getUser()) {
                $this->addFlash('warning', ' Veuillez vous connecter pour ajouter un commentaire.');
                return $this->redirectToRoute('app_login');
            }

            $commentaire->setEvenement($evenement);
            $commentaire->setUtilisateur($this->getUser());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('details_evenement', [
                'idEvent' => $evenement->getId()
            ]);
        }

    




        return $this->render('evenement/details.html.twig', [
            "evenement" => $evenement,
            "createur" => $createur,
            "commentaires" => $commentaires,
            'formAddCommentaire' => $form->createView(),

        ]);
    }

    /**
     * @ROute("/Adminn", name = "app_admin")
     */

    public function admin(EvenementRepository $e): Response
    {

        $evenementsAvenir = $e->findEvenementsAvenir();

        return $this->render('user/admin.html.twig', [
            'evenementsAvenir' => $evenementsAvenir
        ]);
    }



    /**
     * @Route("/organisateur",name="app_oragnisateur")
     */

    public function Organisateur(EvenementRepository $ev)
    {
        $user = $this->getUser();
        $evenements = $ev->findBy(["createur" => $user]);
        return $this->render('user/organisateur.html.twig', [
            'user' => $user,
            'evenements' => $evenements
        ]);
    }




    /**
     * @Route("/admin/evenement/{id}/validate", name="admin_event_validate")
     */
    public function validateEvent(Evenement $evenement = null, ManagerRegistry $doctrine, Security $security)
    {

        $isAdmin = $security->isGranted('ROLE_ADMIN');
        if ($isAdmin) {
            if ($evenement) {
                $evenement->setStatut('validé');
                $entityManager = $doctrine->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('app_categorie');
            }
            return $this->redirectToRoute('app_categorie');
        }
        return $this->redirectToRoute('app_categorie');
    }


    /**
     * @Route("/admin/evenement/{id}/refuse", name="admin_event_refuse")
     */
    public function refuseEvent(Evenement $evenement = null, ManagerRegistry $doctrine, Security $security)
    {
        $isAdmin = $security->isGranted('ROLE_ADMIN');
        if ($isAdmin) {
            if ($evenement) {
                $evenement->setStatut('refusé');
                $entityManager = $doctrine->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('app_categorie');
            }
            return $this->redirectToRoute('app_categorie');
        }
        return $this->redirectToRoute('app_categorie');
    }
}
