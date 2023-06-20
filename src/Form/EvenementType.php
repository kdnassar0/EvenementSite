<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Categorie;
use App\Entity\Evenement;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\VarDumper\Cloner\Data;

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
            ->add('prix',NumberType::class,['label'=>'Prix :'])

            ->add('image',FileType::class, [
                'data_class' =>null,
                'constraints' => [
                    new File ([
                        'maxSize'=>'1024k',
                        'mimeTypes'=> [
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid image',
                    ]),
                ],
                'label'=>'Ajoutez une image de votre événement :',
            ])

            

            ->add('imageAffiche',FileType::class, [
                'data_class' =>null,
                'constraints' => [
                    new File ([
                        'maxSize'=>'1024k',
                        'mimeTypes'=> [
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid image',
                    ]),
                ],
                'label'=>'Ajoutez une image de votre Affiche (facultative) :',
                'required' => false,
              

                
            ])
            ->add('categorie',EntityType::class,
            ['class'=>Categorie::class,'choice_label'=>'nom'])
          
          

            ->add('submit',SubmitType::class,['label'=>'<i class="fa-regular fa-paper-plane"></i>',
            'label_html' => true])
        ;   
    }

  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
