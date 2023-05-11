<?php

namespace App\Controller;

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
        $evenements = $e->findEvenements();

    
        $rdvs = [] ; 
    
        foreach($evenements as $evenement){
          $rdvs [] =[
            'id' => $evenement->getId(),
            'date_debut'=>$evenement->getDateDebut(),
            'date_fin' =>$evenement->getDateFin(),
            'nom'=>$evenement->getNom(),
          ] ;
        }
    
        $data =json_encode($rdvs) ;

        return $this->render('api/index.html.twig',compact('data'));
    }
}
