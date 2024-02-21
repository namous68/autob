<?php

namespace App\Form;

use App\Entity\Garage;
use App\Entity\Professionnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GarageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('numeroTel')
            ->add('adress1')
            ->add('adress2')
            ->add('ville')
            ->add('codePostale')
            ->add('professionnel' ,GarageType::class,[
                'class' => Professionnel::class,
                'choice_label' => 'nom', // Ou toute autre propriété à afficher dans le champ select
                
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Garage::class,
        ]);
    }
}
