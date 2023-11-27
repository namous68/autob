<?php

namespace App\Form;

use App\Entity\Carburant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use App\Form\ImageType;
use App\Form\MarqueType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', TextType::class)
        ->add('datePublication', DateTimeType::class)
        ->add('description', TextType::class)
        ->add('ref', TextType::class)
        ->add('misEnCirculation', IntegerType::class)
        ->add('kilometrage', IntegerType::class)
        ->add('prix', IntegerType::class)
        ->add('carburant', EntityType::class, [
            'class' => Carburant::class,
            'choice_label' => 'type',
            
        ])

        
        ->add('model', ModelType::class, [
           
            'required' => true,
        ])
        ->add('marque', MarqueType::class, [
            
            'required' => true,
        ])
    ->add('garage', GarageType::class)
             // Ajoutez un champ pour la collection d'images
             ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}