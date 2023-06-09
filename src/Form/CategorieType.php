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
            
            ->add('submit',SubmitType::class,['label'=>'<i class="fa-regular fa-paper-plane"></i>',
            'label_html' => true]);

            }  
            
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
