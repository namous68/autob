<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\Carburant;
use App\Entity\Garage;
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


class DashboardController extends AbstractDashboardController
{
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
        if ($this->isGranted('ROLE_ADMIN')){
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('Annonce', 'fas fa-list', Annonce::class);
        yield MenuItem::linkToCrud('Carburant', 'fas fa-list', Carburant::class);
        yield MenuItem::linkToCrud('Garage', 'fas fa-list', Garage::class);
        yield MenuItem::linkToCrud('Marque', 'fas fa-list', Marque::class);
        yield MenuItem::linkToCrud('Model', 'fas fa-list', Model::class);
        yield MenuItem::linkToCrud('Professionnel', 'fas fa-list', Professionnel::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);



        }if ($this->isGranted('ROLE_PROFESSIONAL')) {
        
            yield MenuItem::linkToCrud('Annonce', 'fas fa-list', Annonce::class);
            yield MenuItem::linkToCrud('Garage', 'fas fa-list', Garage::class);
        
    
        }
       // return $menuItems;
    }


    

    


}
