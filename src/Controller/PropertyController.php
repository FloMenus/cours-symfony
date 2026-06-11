<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\PropertyType;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PropertyController extends AbstractController
{
    #[Route('/property/new', name: 'property_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $property->setHost($user);
            $property->setStatus('draft');
            $property->setCreatedAt(new \DateTimeImmutable());

            $em->persist($property);
            $em->flush();

            return $this->redirectToRoute('property_show', ['id' => $property->getId()]);
        }

        return $this->render('user/pages/property_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/property/{id}', name: 'property_show')]
    public function show(Property $property): Response
    {
        return $this->render('user/pages/property_show.html.twig', [
            'property' => $property,
        ]);
    }

    #[Route('/property/{id}/book', name: 'property_book', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function book(Property $property, Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation, [
            'max_guests' => $property->getMaxGuests(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nights = $reservation->getCheckinDate()->diff($reservation->getCheckoutDate())->days;

            $reservation->setProperty($property);
            $reservation->setGuest($this->getUser());
            $reservation->setStatus('confirmed');
            $reservation->setCurrency('EUR');
            $reservation->setCleaningFee($property->getCleaningFee());
            $reservation->setSecurityDeposit($property->getSecurityDeposit());
            $reservation->setTotalPrice((string) ($nights * (float) $property->getPricePerNight()));

            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('reservation_confirmed', ['id' => $reservation->getId()]);
        }

        return $this->render('user/pages/property_book.html.twig', [
            'property' => $property,
            'form'     => $form,
        ]);
    }

    #[Route('/reservation/{id}/confirmed', name: 'reservation_confirmed')]
    public function confirmed(Reservation $reservation): Response
    {
        return $this->render('user/pages/reservation_confirmed.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
