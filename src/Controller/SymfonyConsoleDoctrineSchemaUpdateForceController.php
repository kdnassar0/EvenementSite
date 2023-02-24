<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SymfonyConsoleDoctrineSchemaUpdateForceController extends AbstractController
{
    /**
     * @Route("/symfony/console/doctrine/schema/update/force", name="app_symfony_console_doctrine_schema_update_force")
     */
    public function index(): Response
    {
        return $this->render('symfony_console_doctrine_schema_update_force/index.html.twig', [
            'controller_name' => 'SymfonyConsoleDoctrineSchemaUpdateForceController',
        ]);
    }
}
