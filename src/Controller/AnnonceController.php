<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{

    private $entityManager;

    // Injection de l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_home')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();
        return $this->render('index.html.twig',[
        'annonces' => $annonces
    ]);
    }


    #[Route('/new', name: 'app_annonce_new')]
    public function new(Request $request): Response
    {
        // Créez une nouvelle instance de l'entité Annonce
        $annonce = new Annonce();

        // Créez un formulaire associé à l'entité Annonce
        $form = $this->createForm(AnnonceType::class, $annonce);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez l'annonce dans la base de données
           // $entityManager = $this->getDoctrine()->getManager();
           $this->entityManager->persist($annonce);
           $this->entityManager->flush();

            // Redirigez l'utilisateur vers la page d'accueil ou une autre page
            return $this->redirectToRoute('app_home');
        }

        // Affichez le formulaire dans le template
        return $this->render('/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_annonces_show', methods: ['GET'])]
    public function show($id, AnnonceRepository $annonceRepository): Response
    {
         // Récupérer l'annonce depuis la base de données
         $annonce = $annonceRepository->find($id);

         // Vérifier si l'annonce existe
         if (!$annonce) {
             throw $this->createNotFoundException('Annonce non trouvée');
         }
 
         // Afficher les détails de l'annonce dans le template
         return $this->render('/show.html.twig', [
             'annonce' => $annonce,
         ]);
    }

    #[Route('/get_models/{marque}', name: 'get_models', methods: ['GET'])]
    public function getModels(Marque $marque): JsonResponse
    {
        $repository = $this->entityManager->getRepository(Model::class);
        $models = $repository->findBy(['marque' => $marque]);

        $modelArray = [];
        foreach ($models as $model) {
            $modelArray[] = [
                'id' => $model->getId(),
                'modelNom' => $model->getModelNom(),
            ];
        }

        return new JsonResponse($modelArray);
    }

}
