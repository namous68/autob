<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Repository\GarageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GarageController extends AbstractController
{
    #[Route('/garage', name: 'app_garage')]
    public function index(GarageRepository $garageRepository): Response
{
    // Récupérer la liste de tous les garages
    $garages = $garageRepository->findAll();

    // Rendre le template Twig en passant la liste des garages
    return $this->render('garage.html.twig', [
        'garages' => $garages,
    ]);
}

    #[Route('garage/{id}', name: 'app_garage_show', methods: ['GET'])]
public function show($id, GarageRepository $garageRepository): Response
{
    // Vérifiez si l'argument '$id' est correctement fourni
    if ($id === null) {
        throw new NotFoundHttpException('Garage not found.');
    }

    // Récupérer le garage depuis la base de données
    $garage = $garageRepository->find($id);

    // Vérifier si le garage existe
    if (!$garage) {
        throw $this->createNotFoundException('Garage non trouvé');
    }

    // Récupérer la liste de tous les garages (vous pouvez ajuster selon votre besoin)
    $garages = $garageRepository->findAll();

    // Afficher la liste des garages dans le template
    return $this->render('garage.html.twig', [
        'garages' => $garages,
    ]);
}

public function list(EntityManagerInterface $entityManager): Response
{
    $garages = $entityManager->getRepository(Garage::class)->findAll();

    return $this->render('garage/list.html.twig', [
        'garages' => $garages,
    ]);
}
}
