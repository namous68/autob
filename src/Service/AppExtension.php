<?php
// src/Twig/AppExtension.php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    public function getFilters()
    {
        return [
            new TwigFilter('customFilter', [$this, 'customFilter']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('redirectUrl', [$this, 'generateRedirectUrl']),
        ];
    }

    public function generateRedirectUrl(string $routeName): string
    {
        return $this->urlGenerator->generate($routeName);
    }


    public function customFilter($value)
    {
        // Votre logique de filtre ici
        return $value;
    }

    public function customFunction($arg1, $arg2)
    {
        // Votre logique de fonction ici
        return $arg1 . $arg2;
    }
}

