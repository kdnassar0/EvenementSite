<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Entity\Commentaire;
use App\Form\CategorieType;
use App\Form\CommentaireType;
use App\Repository\CategorieRepository;
use App\Repository\EvenementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CategorieController extends AbstractController
{

    /**
     * @Route("/categorie", name="app_categorie")
     * @Route("/add/categorie" , name="add_categorie")
     */
    public function index(CategorieRepository $ca, ManagerRegistry $doctrine, Categorie $categorie = null, Request $request, SluggerInterface $slugger, EvenementRepository $e): Response

    {

        $evenementsAvenir = $e->findEvenementsAvenir();
        $categories = $ca->findBy([], ['nomCategorie' => 'ASC']);




        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $categorie = $form->getData();
                $categorie->setImage($newFilename);
                $entityManager = $doctrine->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('categorie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload

                }



                return $this->redirectToRoute('app_categorie');
            }
        }
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'formAddCategorie' => $form->createView(),
            'evenementsAvenir' => $evenementsAvenir,

        ]);
    }







    /**
     * @Route("/categorie/{id}/suprimmer", name="supprimer_categorie")
     */

    public function supprimerCategorie(Categorie $categorie, ManagerRegistry $doctrine, Filesystem $filesystem)
    {

        $entityManager = $doctrine->getManager();
        $entityManager->remove($categorie);
        // Récupérer le chemin du fichier image de la salle à supprimer
        $imagePath = $this->getParameter('categorie_directory') . '/' . $categorie->getImage();

        $filesystem->remove($imagePath);
        $entityManager->flush();


        return $this->redirectToRoute('app_categorie');
    }
}
