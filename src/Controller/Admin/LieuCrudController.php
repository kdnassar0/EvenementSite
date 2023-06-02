<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
            yield Field::new('nom'),
            yield Field::new('description'),
            yield Field::new ('ville'),
            yield Field::new ('adress'),
            yield ImageField::new('image')
            ->setBasePath('public/images/lieu')
        ->setUploadDir('public/images/lieu'),

            yield ImageField::new('imageSalle')
            ->setBasePath('public/images/lieu')
        ->setUploadDir('public/images/lieu')];

    }

}
