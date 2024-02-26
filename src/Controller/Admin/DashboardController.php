<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\Carburant;
use App\Entity\Garage;
use App\Repository\GarageRepository;
use App\Entity\Image;
use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Professionnel;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\AdminUrlGenerator;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfessionnelType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud as ConfigCrud;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use EasyCorp\Bundle\EasyAdminBundle\Crud\Crud;




class DashboardController extends AbstractDashboardController
{
    private $security;
    private $entityManager;
    private $garageRepository;

    public function __construct(Security $security, EntityManagerInterface $entityManager, GarageRepository $garageRepository)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->garageRepository = $garageRepository;
    }

    #[Route('/admin/garages', name: 'admin_garages')]
    public function listGarages( Security $security, GarageRepository $garageRepository): Response
    {
        /**
         * @var User $professionnel
         */
        $professionnel = $security->getUser();

        // Récupérer les garages du professionnel connecté
        $garages = $garageRepository->findBy(['professionnel' => $professionnel->getProfessionnel()]);

        // Afficher les garages
        return $this->render('/garage.html.twig', [
            'garages' => $garages,
            
            
        ]);
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(AnnonceCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('===AutoB CMS===');
    }

    

    public function configureMenuItems(): iterable
    {
        if ($this->isGranted('ROLE_ADMIN')) {

            
            
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

            
            // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
            yield MenuItem::linkToCrud('Les Annonce', 'fas fa-bullhorn', Annonce::class);
            yield MenuItem::linkToCrud('Message', 'fas fa-address-book', Contact::class);
            yield MenuItem::linkToCrud('Carburant', 'fas fa-car', Carburant::class);
            yield MenuItem::linkToCrud('Les Garage', 'fas fa-warehouse', Garage::class);
            yield MenuItem::linkToCrud('Les Marque', 'fas fa-list', Marque::class);
            yield MenuItem::linkToCrud('Les Model', 'fas fa-list', Model::class);
            yield MenuItem::linkToCrud('Professionnel', 'fas fa-list', Professionnel::class);
            yield MenuItem::linkToCrud('List User', 'fas fa-list', User::class);
        }
        if ($this->isGranted('ROLE_PROFESSIONAL')) {

            /**
             * @var User $user
             */
            $user = $this->getUser();

            if ($user && $user->getProfessionnel()) {

                 $professionnelId = $user->getProfessionnel()->getId();

                $garages = $this->entityManager->getRepository(Garage::class)->findBy(['professionnel' => $user->getProfessionnel()]);

                // dd($garages);
                
         

        
    

                //foreach ($garages as $garage) {
                   // yield MenuItem::linkToCrud($garage->getNom(), 'fas fa-list', Garage::class)
                    //->setController(GarageCrudController::class)
                  //  ->setEntityId($garage->getId()
                // );
              //  }

                // foreach ($garages as $garage) {
                //     yield MenuItem::subMenu($garage->getNom(), 'fas fa-list')->setSubItems([
                //         MenuItem::linkToCrud($garage->getNom(), 'fas fa-list', Garage::class)
                //             ->setController(GarageCrudController::class)
                //             ->setDefaultSort(['id' => 'ASC']),
                //     ]);
                // }
            //} else {
              //  echo "Erreur.. Le professionnel est introuvable.";
            //}
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');


            
            yield MenuItem::linkToCrud('Garage', 'fas fa-warehouse', Garage::class);
            yield MenuItem::linkToCrud('Annonce', 'fas fa-bullhorn', Annonce::class);

            yield MenuItem::linkToCrud('Contact', 'fas fa-address-book', Contact::class);
        }
        // return $menuItems;
    }
    }





    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function contactMessages(): Response
    {
        // Récupérez les messages de contact
        $contactMessages = $this->entityManager->getRepository(Contact::class)->findAll();
        // Affichez les messages 
        return $this->render('admin/contact.html.twig', [
            'contactMessages' => $contactMessages,
        ]);
    }


    
}
