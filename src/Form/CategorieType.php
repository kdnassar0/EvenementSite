<?php

namespace App\Form;

use App\Entity\Categorie;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            
            ->add('image',FileType::class,
            [
            'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            // 'mimeTypes' => [
                                
                            //     'image/PNG',
                            //     'image/JPG'
                            //                ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                                ])
                            ]
        
            ])
            
            ->add('submit',SubmitType::class);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
