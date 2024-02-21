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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;


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
    public function listGarages(): Response
    {
        // Récupérer le professionnel connecté
        $professionel = $this->security->getUser();

        // Récupérer les garages du professionnel connecté
        $garages = $this->entityManager->getRepository(Garage::class)->findBy(['professionel' => $professionel]);

        // Afficher les garages
        return $this->render('admin/garages.html.twig', [
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
            ->setTitle('Admin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
            // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
            yield MenuItem::linkToCrud('Les Annonce', 'fas fa-list', Annonce::class);
            yield MenuItem::linkToCrud('Message', 'fas fa-list', Contact::class);
            yield MenuItem::linkToCrud('Carburant', 'fas fa-list', Carburant::class);
            yield MenuItem::linkToCrud('Les Garage', 'fas fa-list', Garage::class);
            yield MenuItem::linkToCrud('Les Marque', 'fas fa-list', Marque::class);
            yield MenuItem::linkToCrud('Les Model', 'fas fa-list', Model::class);
            yield MenuItem::linkToCrud('Professionnel', 'fas fa-list', Professionnel::class);
            yield MenuItem::linkToCrud('List User', 'fas fa-list', User::class);

            //role pour un professionnel
        }
        if ($this->isGranted('ROLE_PROFESSIONAL')) {


            /* $user = $this->getUser();

    if ($user && $user->getProfessionnel()) {
        // Récupérer l'id
        $professionnelId = $user->getProfessionnel()->getId();

        // Récupérer les garages du pro
        $garages = $this->entityManager->getRepository(Garage::class)->findByProfessionnelId($professionnelId);

        foreach ($garages as $garage) {
        
            yield MenuItem::linkToCrud($garage->getName(), 'fas fa-list', Garage::class)
                ->setController(GarageCrudController::class)
                ->setDefaultSort(['id' => 'ASC']); 
        
    } 
            yield MenuItem::linkToCrud('Garage', 'fas fa-list', Garage::class);
            yield MenuItem::linkToCrud('Annonce', 'fas fa-list', Annonce::class);
            
            yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);
         
}else {
                echo "Une erreur s'est produite. Le professionnel est introuvable.";
            }*/


            yield MenuItem::linkToCrud('Garage', 'fas fa-list', Garage::class);
            yield MenuItem::linkToCrud('Annonce', 'fas fa-list', Annonce::class);

            yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);
        }
        // return $menuItems;
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
