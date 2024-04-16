<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Stade;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    

    #[Route('/rev', name: 'app_res', methods: ['GET'])]
    public function res(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/_back_rev.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/stadeFront/{stadeId}/new', name: 'app_stade_reservation_new', methods: ['GET', 'POST'])]
    public function newForStade(Request $request, EntityManagerInterface $entityManager, $stadeId): Response
    {
        $stade = $entityManager->getRepository(Stade::class)->find($stadeId);
        if (!$stade) {
            throw $this->createNotFoundException('No stade found for id '.$stadeId);
        }

        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setIdStade($stade);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    #[Route('/{id}/sh', name: 'app_sh', methods: ['GET'])]
    public function showD(Reservation $reservation): Response
    {
        return $this->render('reservation/showD.html.twig', [
            'reservation' => $reservation,
        ]);
    }

        #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ReservationType::class, $reservation);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reservation/edit.html.twig', [
                'reservation' => $reservation,
                'form' => $form,
            ]);
        }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/del/{id}', name: 'app_del', methods: ['POST'])]
    public function del(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }
    
        
        return $this->redirectToRoute('app_res', [], Response::HTTP_SEE_OTHER);
    }

    
}