<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CategorieCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/Admin", name="admin")
     */
    public function index(): Response
    {
        $routebuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routebuilder->setController(CategorieCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EvenementSite');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('back site', 'fa fa-home','app_categorie');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('categorie', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('lieu', 'fas fa-list', Lieu::class);
    }
}
