<?php

namespace App\Form;

use App\Entity\Professionnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfetionnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adressMail')
            ->add('numeroTel')
            ->add('numeroSiret')
            ->add('login')
            ->add('motDePass')
            ->add('estAdmin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professionnel::class,
        ]);
    }
}
