<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/pages/dashboard.html.twig', [
            'menu_items' => [
                ['label' => 'Vue générale', 'route' => 'admin_dashboard', 'href' => $this->generateUrl('admin_dashboard')],
                ['label' => 'Réservations', 'route' => 'admin_reservations', 'href' => $this->generateUrl('admin_reservations')],
                ['label' => 'Utilisateurs', 'route' => 'admin_dashboard', 'href' => '#'],
                ['label' => 'Annonces', 'route' => 'admin_dashboard', 'href' => '#'],
                ['label' => 'Paiements', 'route' => 'admin_dashboard', 'href' => '#'],
            ],
            'stats' => [
                ['label' => 'Réservations actives', 'value' => '248', 'delta' => '+12% ce mois'],
                ['label' => 'Revenu brut', 'value' => '47 800€', 'delta' => '+8.4% ce mois'],
                ['label' => 'Nouveaux utilisateurs', 'value' => '1 124', 'delta' => '+16% ce mois'],
                ['label' => 'Litiges ouverts', 'value' => '7', 'delta' => '-2 vs semaine passée'],
            ],
            'recent_bookings' => [
                ['reference' => 'RSV-9201', 'guest' => 'Lina Moreau', 'property' => 'Loft lumineux • Lyon', 'amount' => '435€', 'status' => 'Confirmée'],
                ['reference' => 'RSV-9202', 'guest' => 'Arthur Klein', 'property' => 'Maison surf • Hossegor', 'amount' => '630€', 'status' => 'En attente'],
                ['reference' => 'RSV-9203', 'guest' => 'Nora Simon', 'property' => 'Villa calme • Nice', 'amount' => '1 280€', 'status' => 'Confirmée'],
                ['reference' => 'RSV-9204', 'guest' => 'Yanis Benali', 'property' => 'Studio central • Paris', 'amount' => '290€', 'status' => 'En attente'],
            ],
            'active_route' => 'admin_dashboard',
        ]);
    }

    #[Route('/admin/reservations', name: 'admin_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAllOrderedByDate();

        return $this->render('admin/pages/reservation_history.html.twig', [
            'reservations' => $reservations,
            'menu_items' => [
                ['label' => 'Vue générale', 'route' => 'admin_dashboard', 'href' => $this->generateUrl('admin_dashboard')],
                ['label' => 'Réservations', 'route' => 'admin_reservations', 'href' => $this->generateUrl('admin_reservations')],
                ['label' => 'Utilisateurs', 'route' => 'admin_dashboard', 'href' => '#'],
                ['label' => 'Annonces', 'route' => 'admin_dashboard', 'href' => '#'],
                ['label' => 'Paiements', 'route' => 'admin_dashboard', 'href' => '#'],
            ],
            'active_route' => 'admin_reservations',
        ]);
    }
}
