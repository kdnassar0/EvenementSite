<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date_debut')
            ->add('date_fin')
            ->add('nb_des_palces')
            ->add('description')
            ->add('statue')
            ->add('prix')
            ->add('paiement_valid')
            ->add('image')
            ->add('categorie')
            ->add('salles')
            ->add('createur')
            ->add('participants')
            ->add('submit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
