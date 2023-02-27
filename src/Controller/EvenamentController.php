<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenamentController extends AbstractController
{
    /**
     * @Route("/evenament", name="app_evenament")
     */
    public function index(): Response
    {
        return $this->render('evenament/index.html.twig', [
            'controller_name' => 'EvenamentController',
        ]);
    }
}
