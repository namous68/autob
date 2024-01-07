<?php

namespace App\Form;

use App\Entity\Carburant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use App\Form\ImageType;
use App\Form\MarqueType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\File;

class AnnonceType extends AbstractType
{
    private $imageFile;

    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('datePublication', DateTimeType::class)
            ->add('description', TextType::class)
            ->add('ControleTechnique', TextType::class)
            ->add('PremiereMain', TextType::class)
            ->add('Couleur', TextType::class)
            ->add('NombreDePortes', TextType::class)
            ->add('VolumeDeCoffre', TextType::class)
            ->add('Rechargeable', TextType::class)
            ->add('PuissanceFiscale', TextType::class)
            ->add('Garantie', TextType::class)
            ->add('ref', TextType::class)
            ->add('misEnCirculation', IntegerType::class)
            ->add('kilometrage', IntegerType::class)
            ->add('prix', IntegerType::class)
            ->add('carburant', EntityType::class, [
                'class' => Carburant::class,
                'choice_label' => 'type',
            ])
            ->add('marque', MarqueType::class, [
                'required' => true,
            ])
            ->add('model', ModelType::class, [
                'required' => true,
            ])
            ->add('garage', GarageType::class)
            ->add('cameraDeRecul', CheckboxType::class, [
                'label' => 'Variable Booléenne 1',
                'required' => false,
            ])
            ->add('gps', CheckboxType::class, [
                'label' => 'Variable Booléenne 2',
                'required' => false,
            ])
            ->add('bluetooth', CheckboxType::class, [
                'label' => 'Variable Booléenne 1',
                'required' => false,
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Variable Booléenne 2',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'label' => 'Image',
                'required' => false,
                'allow_delete' => true, // Optionnel : permet de supprimer l'image actuelle
                'download_label' => 'Télécharger', // Optionnel : texte du lien de téléchargement
                'attr' => [
                    'accept' => 'image/*',
                ],
                ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'methode' => 'GET',
        ]);
    }

    
}