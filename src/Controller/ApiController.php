<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="app_api")
     */
    public function index(EvenementRepository $e): Response
    {
        $evenements = $e->findAll();

    
        $rdvs = [] ; 
    
        foreach($evenements as $evenement){
          $rdvs [] =[
            'id' => $evenement->getId(),
            'start'=>$evenement->getDateDebut()->format('Y-m-d H:i:s'),
            'end' =>$evenement->getDateFin()->format('Y-m-d H:i:s' ),
            'title'=>$evenement->getNom(),
          ] ;
        }
    
        $rdvs =json_encode($rdvs);

        return $this->render('api/index.html.twig',[
            'data' => $rdvs
        ]);
    }
}


