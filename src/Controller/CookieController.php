<?php
// src/Controller/CookieController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CookieController extends AbstractController
{
    #[Route('/policy.html.twig', name: 'cookie_policy')]
    public function cookiePolicy(): Response
    {
        return $this->render('/policy.html.twig');
    }
}
