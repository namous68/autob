<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $annonce = $options['annonce'];

        

        $builder
        ->add('Name', TextType::class, [
            'label' => 'Your Name',
            
        ])
        ->add('Email', EmailType::class, [
            'label' => 'Your Email',
            
        ])
        ->add('Message', TextareaType::class, [
            'label' => 'Your Message',
           
        ])
            
           // ->add('CreatAt')
            
         
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'annonce'=> null,
        ]);
    }
}
