<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/admin/evenement/{id}/validate", name="admin_event_validate")
     */
    public function validateEvent(Evenement $evenement,ManagerRegistry $doctrine)
    {
        {
            $evenement->setStatue('validé');
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
        $evenement->setStatue('refusé');
        $entityManager=$doctrine->getManager();
        $entityManager->flush() ;

        return $this->redirectToRoute('app_evenement');
    }
}
