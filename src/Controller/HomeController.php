<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\PropertyRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findAll();
        $nightlyPrices = array_map(
            static fn ($property): int => (int) round((float) $property->getPricePerNight()),
            $properties
        );
        $lowestPrice = $nightlyPrices !== [] ? min($nightlyPrices) : null;

        return $this->render('user/pages/home.html.twig', [
            'hero_metrics' => [
                ['value' => (string) count($properties), 'label' => 'Logements'],
                ['value' => '24h', 'label' => 'Réponse hôte'],
                ['value' => $lowestPrice !== null ? sprintf('%d€', $lowestPrice) : '--', 'label' => 'À partir de'],
            ],
            'inspirations' => [
                [
                    'tag' => 'Week-end nature',
                    'city' => 'Annecy',
                    'description' => 'Lacs, montagnes et chalets premium.',
                    'gradient' => 'from-rose-400 to-coral-500',
                ],
                [
                    'tag' => 'City break',
                    'city' => 'Lisbonne',
                    'description' => 'Appartements design avec vue.',
                    'gradient' => 'from-cyan-500 to-blue-600',
                ],
                [
                    'tag' => 'Soleil',
                    'city' => 'Palma',
                    'description' => 'Maisons avec piscine privée.',
                    'gradient' => 'from-amber-400 to-orange-500',
                ],
                [
                    'tag' => 'Retraite zen',
                    'city' => 'Bali',
                    'description' => 'Villas immersives et jungle spa.',
                    'gradient' => 'from-emerald-500 to-teal-600',
                ],
            ],
            'properties' => $properties,
            'footer_columns' => [
                [
                    'title' => 'Explorer',
                    'links' => ['Logements', 'Expériences', 'Nouveautés'],
                ],
                [
                    'title' => 'Hébergement',
                    'links' => ['Devenir hôte', "Centre d'aide", 'Forum communauté'],
                ],
                [
                    'title' => 'Entreprise',
                    'links' => ['À propos', 'Presse', 'Confidentialité'],
                ],
            ],
        ]);
    }

}
