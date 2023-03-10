<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Evenement;
use App\Repository\UserRepository;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
     * @ROute("/Admin", name = "app_admin")
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
     */
    public function validateEvent(Evenement $evenement,ManagerRegistry $doctrine)
    {
        {
            $evenement->setStatut('validé');
            $entityManager=$doctrine->getManager();
            $entityManager->flush() ;
    
            return $this->redirectToRoute('app_evenement');
        }
        return $this->render('categorie/add.html.twig',[

        ]);

    }

    /**
     * @Route("/admin/evenement/{id}/refuse", name="admin_event_refuse")
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
      public function detailsEvenement(ManagerRegistry $doctrine,Evenement $evenement=null,$id)
      {
 
        $participants = $evenement->getParticipant();
          $evenement=$doctrine->getRepository(Evenement::class)->findOneBy(['id'=>$id]) ;
       
         
          return $this->render('evenement/details.html.twig',[
              "evenement"=>$evenement,
              "participants" => $participants
           
        ]);

      }

}
