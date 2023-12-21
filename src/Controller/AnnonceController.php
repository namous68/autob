<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Annonce;
use App\Entity\Image;
use App\Form\AnnonceType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\SearchAnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\AnnonceSearchService;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnnonceController extends AbstractController
{

    private $entityManager;
    private $slugger;
    private $annonceSearchService;
    private $annonceImageUploader;


    // Injection de l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, AnnonceSearchService $annonceSearchService)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->annonceSearchService = $annonceSearchService;
        }


    #[Route('/', name: 'app_home')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser();
        $annonces = $annonceRepository->findAll();
        return $this->render('index.html.twig',[
        'annonces' => $annonces,
        'user' => $user,
    ]);
    }

    

    #[Route('annonce/new', name: 'app_annonce_new')]
    public function new(Request $request): Response
    {
        /**
         * @IsGranted("ROLE_PROFESSIONAL")
         */
        $user = $this->getUser();
      
        // Créez une nouvelle instance de l'entité Annonce
        $annonce = new Annonce();
        // Créez un formulaire associé à l'entité Annonce
        $form = $this->createForm(AnnonceType::class, $annonce);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
                 * @var UploadedFile $uploadedFile
                 */
            $uploadedFile = $form['imageFile']->getData();

        
            if ($uploadedFile instanceof UploadedFile) {
                $newFilename = Uuid::v4() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move($this->getParameter('kernel.project_dir') . 'public/media', $newFilename);

                // Créez un nouvel objet File avec le nom de fichier et le contenu
               // $uploadedFile = new File($this->getParameter('kernel.project_dir') . 'public/media/' . $newFilename);
                // Assurez-vous que la méthode setImageFile accepte un UploadedFile
                $annonce->setImageFile($uploadedFile);
            } 
        
            // Enregistrez l'annonce dans la base de données
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

    #[Route('annonce/{id}', name: 'app_annonces_show', methods: ['GET'])]
    public function show($id, AnnonceRepository $annonceRepository): Response
    {
         // Récupérer l'annonce depuis la base de données
         $annonce = $annonceRepository->find($id);

         // Vérifier si l'annonce existe
        //if (!$annonce) {
          //   throw $this->createNotFoundException('Annonce non trouvée');
         //}
 
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

    //action pour la recherche 
    #[Route('/search', name: 'app_annonces_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $marque = $request->query->get('marque');
    $modele = $request->query->get('model');
    $prixMin = $request->query->get('prix_min');

     // Utilisez le service de recherche pour obtenir les résultats
     $annonces = $this->annonceSearchService->searchByCriteria($marque, $modele, $prixMin);




    return $this->render('search_results.html.twig', [
        'annonces' => $annonces,
    ]);

    
}


}
