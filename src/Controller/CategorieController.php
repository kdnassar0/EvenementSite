<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{

     /**
     * @Route("/categorie", name="app_categorie")
     * @Route("/add/categorie" , name="add_categorie")
     */
    public function index(CategorieRepository $ca,ManagerRegistry $doctrine,Categorie $categorie = null,Request $request): Response
   
    {
        $categories = $ca->findBy([],['nomCategorie'=>'ASC']);

        $form =$this->createForm(CategorieType::class,$categorie) ;
        $form->handleRequest($request) ;
      
        if($form->isSubmitted() && $form->isValid())
        {
           
                $categorie=$form->getData() ; 
                $entityManager=$doctrine->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush() ; 

                
         return $this->redirectToRoute('app_categorie') ;
                
        }

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories , 
            'formAddCategorie'=>$form->createView()
        ]);
    }


    /**
     * @Route("/categorie/{id}/suprimmer", name="supprimer_categorie")
     */

     public function supprimerCategorie(Categorie $categorie, ManagerRegistry $doctrine)
     {
        
        $entityManager = $doctrine->getManager(); 
        $entityManager->remove($categorie);
        $entityManager->flush();
       
     
        return $this->redirectToRoute('app_categorie') ;

 
    }
 




}
