<?php

namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Symfony\Component\HttpKernel\KernelInterface get('kernel')
 */
class ImageController extends AbstractController 
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/image', name: 'app_image')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ImageController.php',
        ]);
    }

    /**
     * @Route("/upload", name="image_upload")
     */
    public function upload(Request $request, Image $image)
    {
        $image = new Image($this->get('kernel'));

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image->uploadImage($image->getImageFile());

            // ...
        }

        // ...
    }

    public function FindImage(Annonce $annonce): Response
    {
        $images = $this->entityManager->getRepository(Image::class)->findBy(["annonce" => $annonce]);

        // Envoyez les images à votre template Twig
        return $this->render('index.html.twig', [
            'images' => $images,
        ]);
    }


     /**
     * @Route("/image/delete/{id}", name="image_delete", methods={"POST"})
     */
    public function deleteImage(Image $image, ImageRepository $imageRepository): Response
    {
        // Récupérer l'annonce associée avant de supprimer l'image
        $annonce = $image->getAnnonce();

        // Supprimer l'image de la base de données
        $this->entityManager->remove($image);
        $this->entityManager->flush();

        // Supprimer le fichier réel associé à l'image si nécessaire
        // ...

        // Rediriger vers la page de l'annonce après la suppression
        return $this->redirectToRoute('annonce_show', ['id' => $annonce->getId()]);
    }
}
