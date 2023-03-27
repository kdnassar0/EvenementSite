<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvenementType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['label'=>"Nom de l'événement :"])
            ->add('date_debut',DateTimeType::class, [
                'widget' => 'single_text','label'=>'Date de debut :'
            ])
            ->add('date_fin',DateTimeType::class, [
                'widget' => 'single_text','label'=>'Date de fin :'
            ])
            ->add('nb_des_places',NumberType::class,['label'=>'Nombre des places :'])
            ->add('description',TextareaType::class,['label'=>'Déscription :'])
            ->add('prix',NumberType::class)
            ->add('image',FileType::class)
            ->add('categorie',EntityType::class,
            ['class'=>Categorie::class,'choice_label'=>'nom'])
            ->add('salles', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'numero',
                'multiple' => true,
                'expanded' => true,
            ])
          

            ->add('submit',SubmitType::class,['label'=>'Envoyer'])
        ;   
    }

  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
