<?php

namespace App\Form;


use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('description',TextType::class)
            ->add('ville',TextType::class)
            ->add('adress',TextType::class)
            ->add('capacity',TextType::class)
            ->add('image',FileType::class, [
                'constraints' => [
                    new File ([
                        'maxSize'=>'10024k',
                        'mimeTypes'=> [
                            'image/png',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid image',
                    ])
                    ],
                'required' => false,
                'data_class' => null,
            ])
            ->add('imageSalle',FileType::class, [
                'data_class'=>null,
                'constraints' => [
                    new File ([
                        'maxSize'=>'1024k',
                        'mimeTypes'=> [
                            'image/png',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage'=>'Please upload a valid image',
                        
                    ])
                    
                    ],
                'required' => false,
                'data_class' => null,
            ])

            ->add('submit',SubmitType::class,['label'=>'Ajouter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
