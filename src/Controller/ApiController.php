<?php

namespace App\Controller;

use App\Entity\Evenement;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use IntlCalendar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
   

    /**
     * @Route("/api/{id}/edit", name="app_evenet_edit",methods={"PUT" })
     */

     //methode put est une methode qui doit mettre a jour un enregistrement ou le creer s'il n'exsite pas

    public function editEvent(?Evenement $calendar,Request $request,ManagerRegistry $doctrine): Response
    {
         //on recupere les donnees

         $donnees = json_decode($request->getContent());
         if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) 
         ){
            // les donnees sont complete 
            // on insialise un code 
            $code = 200;
            // on verifie si id existe 
            if(!$calendar){
                // on instancie un rendez-vous 
                $calendar =new Evenement;
                // on change le code
                $code = 201 ;
            }
            // on hydrate l'objet avec les donnees
            $calendar->setNom($donnees->title);
            $calendar->setDateDebut(new DateTime($donnees->start));
            $calendar->setDateFin(new dateTime($donnees->end));

            $em =$doctrine->getManager();
            $em->persist($calendar);
            $em->flush();

            return new Response('OK',$code);
         }else{
            // les donnees sont pas complete 


         }


        return $this->render('user/admin.html.twig', [
            'controller_name' => 'ApiController',
      
        ]);
    }
}
