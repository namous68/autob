<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/annonce/{id}/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, $id): Response
    {
        $annonce = $annonceRepository->find($id);
    
        if (!$annonce) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
 // Récupérez les données du formulaire
 $name = $form->get('Name')->getData();
 $email = $form->get('Email')->getData();
 $message = $form->get('Message')->getData();  
      // Créez une nouvelle instance de l'entité Contact
      $contact = new Contact();
      $contact->setName($name);
      $contact->setEmail($email);
      $contact->setMessage($message);

      
    
      // Persistez l'entité Contact dans la base de données
      $this->entityManager->persist($contact);
      $this->entityManager->flush();
            // Redirigez l'utilisateur ou affichez un message de confirmation
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('/contact.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce,
        ]);
    }
}
