<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Salle;
use App\Entity\Evenement;
use Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('capacite',IntegerType::class,
            ['attr'=>['min'=>'1']])
            ->add('numero',IntegerType::class)
            ->add('prix',NumberType::class)
            ->add('discreption',TextType::class)
            ->add('image',UrlType::class) 
            ->add('lieu',EntityType::class,
            [
                'class'=>Lieu::class,
                'choice_label'=>'nom'
            ])
            ->add('submit',SubmitType::class)
        ;
    }



 

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
