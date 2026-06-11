<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'reservation_list')]
    #[IsGranted('ROLE_USER')]
    public function list(ReservationRepository $reservationRepository): Response
    {
        /** @var User $guest */
        $guest = $this->getUser();

        $reservations = $reservationRepository->findByGuestOrderedByDate($guest);

        return $this->render('user/pages/reservation_list.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/reservations/{id}', name: 'reservation_show')]
    public function show(Reservation $reservation): Response
    {
        return $this->render('user/pages/reservation_show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
