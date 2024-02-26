<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Marque;
use App\Entity\Model;
use App\Entity\Annonce;
use App\Entity\Garage;
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
use App\Controller\Urlizer;
use App\Entity\Contact;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;


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
    public function index(AnnonceRepository $annonceRepository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $annonces = $annonceRepository->findAll();

        $repoAnnonce = $entityManager->getRepository(Annonce::class);

        $query = $repoAnnonce->createQueryBuilder('a')
        ->orderBy('a.datePublication', 'DESC')
        ->getQuery();

        $pagination = $paginator->paginate(
            $annonces,
            $request->query->getInt('page', 1),
            9 // Nombre d'annonces par page
        );

        return $this->render('index.html.twig',[
        'annonces' => $annonces,
        'user' => $user,
        'pagination' => $pagination,

    ]);
    }

    

    #[Route('annonce/new', name: 'app_annonce_new')]
public function new(Request $request, SluggerInterface $slugger): Response
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
         /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['imageFile']->getData();
        
        $destination = $this->getParameter('kernel.project_dir').'/public/media/';
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $slugger->slug($originalFilename)->toString() . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        
        $uploadedFile->move(
            $destination, 
            $newFilename
        );
        $annonce->setImageFile($uploadedFile);
        $cameraDeRecul = $form->get('cameraDeRecul')->getData();
        $gps = $form->get('gps')->getData();
        $bluetooth = $form->get('bluetooth')->getData();
        $climatisation = $form->get('climatisation')->getData();
     
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
 
         $garage = $annonce->getGarage();
         // Afficher les détails de l'annonce dans le template
         return $this->render('/show.html.twig', [
             'annonce' => $annonce,
             'garage' => $garage,
             
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
// Récupérer le nom du garage depuis la requête
$nom = $request->query->get('garage');

$annonceRepository = $this->entityManager->getRepository(Annonce::class);
        $annonces = $annonceRepository->findByGarageNom($nom);

        $marque = $request->query->get('marque');
    $modele = $request->query->get('model');
    $prixMin = $request->query->get('prix_min');

     // Utilisez le service de recherche pour obtenir les résultats
     $annonces = $this->annonceSearchService->searchByCriteria($marque, $modele, $prixMin);




    return $this->render('index.html.twig', [
        'annonces' => $annonces,
        'nom' => $nom,
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
        $data = $form->getData();

  // Créez une nouvelle instance de l'entité Contact
  $contact = new Contact();
  $contact->setName($data['name']);
  $contact->setEmail($data['email']);
  $contact->setMessage($data['message']);
  

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

public function sliderAction(EntityManagerInterface $entityManager)
{
    $images = $entityManager->getRepository(Image::class)->findAll(); 

    return $this->render('votre_template.html.twig', [
        'images' => $images,
    ]);
}


#[Route('/annonces-garage/{garageId}', name: 'app_annonces_garage')]
public function annoncesGarage($garageId, Security $security): Response
{
    $annoncesRepository = $this->entityManager->getRepository(Annonce::class);
    
    // Récupérer les annonces pour le garage spécifié par son ID
    $annonces = $annoncesRepository->findBy(['garage' => $garageId]);
    dd($annonces);
    return $this->render('index.html.twig', [
        'annonces' => $annonces,
        'id' => $garageId,
    ]);
}
}



