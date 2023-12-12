<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModelController extends AbstractController
{
    private $entityManager;

    // Injection de l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/model', name: 'app_model')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ModelController.php',
        ]);
    }

    #[Route('/create_model', name: 'create_model')]
    public function createModel(Request $request): Response
    {
        // Création d'une nouvelle instance de Model
        $model = new Model();

// Récupération de l'ID de la marque à partir des données du formulaire
$form = $this->createForm(ModelType::class, $model);    
$marqueId = $request->request->get('marque_id');

        // Récupération de la marque associée (par exemple, à partir des données du formulaire)
       $marqueRepository = $this->entityManager->getRepository(Marque::class);
         $marque = $marqueRepository->find($marqueId);

        // Attribution de la marque au modèle
        $model->setMarque($marque);

        // Création du formulaire
        $form = $this->createForm(ModelType::class, $model);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement du modèle
           // $entityManager = $this->getDoctrine()->getManager();
           $this->entityManager->persist($model);
           $this->entityManager->flush();


            // Redirection vers une autre page ou autre logique après la création réussie
            return $this->redirectToRoute('index');
        }

        // Affichage du formulaire
        return $this->render('model/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
