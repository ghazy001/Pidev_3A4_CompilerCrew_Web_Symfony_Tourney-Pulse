<?php

namespace App\Controller;

use App\Entity\MatchEntity;
use App\Entity\Tournois;
use App\Entity\Equipe;
use App\Form\MatchEntityType;
use App\Form\TournoisType;
use App\Repository\MatchRepository;
use App\Repository\TournoisRepository;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/match/entity')]
class MatchEntityController extends AbstractController
{
    #[Route('/', name: 'app_match_entity_index', methods: ['GET'])]
    public function index(MatchRepository $matchRepository): Response
    {
        return $this->render('match_entity/index.html.twig', [
            'match_entities' => $matchRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_match_entity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matchEntity = new MatchEntity();
        $form = $this->createForm(MatchEntityType::class, $matchEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matchEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_match_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match_entity/new.html.twig', [
            'match_entity' => $matchEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{idMatch}', name: 'app_match_entity_show', methods: ['GET'])]
    public function show(MatchEntity $matchEntity): Response
    {
        return $this->render('match_entity/show.html.twig', [
            'match_entity' => $matchEntity,
        ]);
    }

    #[Route('/{idMatch}/edit', name: 'app_match_entity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MatchEntity $matchEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchEntityType::class, $matchEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_match_entity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match_entity/edit.html.twig', [
            'match_entity' => $matchEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{idMatch}', name: 'app_match_entity_delete', methods: ['POST'])]
    public function delete(Request $request, MatchEntity $matchEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matchEntity->getIdMatch(), $request->request->get('_token'))) {
            $entityManager->remove($matchEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_match_entity_index', [], Response::HTTP_SEE_OTHER);
    }
}
