<?php

namespace App\Controller;


use App\Entity\Evenement;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $evenements=$ev->findBy(["createur"=>$user]);
            return $this->render('user/organisateur.html.twig', [
            'user'=>$user,
            'evenements'=>$evenements
            ]);
    
     }


   
        
        /**
         * @Route("/admin/evenement/{id}/validate", name="admin_event_validate")
         * @IsGranted("ROLE_ADMIN")
         */
        public function validateEvent(Evenement $evenement = null,ManagerRegistry $doctrine)
        {
            {
               if($evenement) {
                $evenement->setStatut('validé');
                $entityManager=$doctrine->getManager();
                $entityManager->flush() ;
        
                return $this->redirectToRoute('app_categorie');

               }

            }
          

        }

    /**
     * @Route("/admin/evenement/{id}/refuse", name="admin_event_refuse")
     * @IsGranted("ROLE_ADMIN")
     */
    public function refuseEvent(Evenement $evenement,ManagerRegistry $doctrine)
    {
        $evenement->setStatut('refusé');
        $entityManager=$doctrine->getManager();
        $entityManager->flush() ;

        return $this->redirectToRoute('app_evenement');
    }

      /**
      * @Route("/details/{id}",name="details_evenement")
      */
      public function detailsEvenement(ManagerRegistry $doctrine,Evenement $evenement=null,$id,Request $request)
      {

        
      
        $commentaire = new Commentaire ();
        $createur = $evenement->getCreateur();
        $evenement=$doctrine->getRepository(Evenement::class)->findOneBy(['id'=>$id]) ;
        $commentaires =$evenement->getCommentaires();

        
          $form = $this->createForm(CommentaireType::class, $commentaire);
          $form->handleRequest($request);

      

      
          
          if ($form->isSubmitted() && $form->isValid()) {
          
      
              $commentaire->setEvenement($evenement);
              $commentaire->setUtilisateur($this->getUser());
              $entityManager = $doctrine->getManager();
              $entityManager->persist($commentaire);
              $entityManager->flush();
      
              return $this->redirectToRoute('details_evenement',['id'=> $evenement->getId()
            ]);
      
           
          }
           
    
       
         
          return $this->render('evenement/details.html.twig',[
              "evenement"=>$evenement,
              "createur" => $createur,
              "commentaires" =>$commentaires,
              'formAddCommentaire'=>$form->createView()
           
        ]);

      }

}
