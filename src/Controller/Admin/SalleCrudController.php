<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Salle;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Salle::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('numero'),
            Field::new('capacite'),
            Field::new('discreption'),
            Field::new('prix'),
            AssociationField::new('lieu'),
            ImageField::new('image')
                ->setBasePath('public/images/salle')
                ->setUploadDir('public/images/salle'),
        ];
    }
    
}
