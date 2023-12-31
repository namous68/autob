<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class AnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonce::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            AssociationField::new('marque'),
            AssociationField::new('model'),
            DateTimeField::new('datePublication'),
            TextField::new('description'),
            TextField::new('ControleTechnique'),
            TextField::new('PremiereMain'),
            TextField::new('Couleur'),
            TextField::new('NombreDePortes'),
            TextField::new('VolumeDeCoffre'),
            TextField::new('Rechargeable'),
            TextField::new('PuissanceFiscale'),
            TextField::new('Garantie'),
            TextField::new('ref'),
            IntegerField::new('misEnCirculation'),
            IntegerField::new('kilometrage'),
            MoneyField::new('prix')->setCurrency('EUR'),
            AssociationField::new('carburant', 'Carburant'),
            
           
            AssociationField::new('garage'),
            
            BooleanField::new('cameraDeRecul', 'cameraDeRecul'),
            BooleanField::new('gps', 'gps'),
            BooleanField::new('bluetooth', 'bluetooth'),
            BooleanField::new('climatisation', 'climatisation'),

           // Ajoutez ce champ pour l'upload d'images
           CollectionField::new('images')
           ->useEntryCrudForm(ImageCrudController::class)

        ];
    }
}
