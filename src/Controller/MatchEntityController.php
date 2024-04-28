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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/match/entity')]
class MatchEntityController extends AbstractController
{
    #[Route('/front', name: 'app_match_entity_index', methods: ['GET'])]
    public function index(MatchRepository $matchRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');

        if ($query) {
            $match_entity = $matchRepository->findBySearchTerm($query);
        }else {
            // If no search query provided, fetch all tournois
            $match_entity = $matchRepository->findAll();
        }

        $match_entity = $paginator->paginate(
            $match_entity, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('match_entity/index.html.twig', ['match_entities'=>$match_entity

        ]);

    }

    #[Route('/back', name: 'app_match_entity_back_index', methods: ['GET'])]
    public function index_back(MatchRepository $matchRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');

        if ($query) {
            $match_entiti = $matchRepository->findBySearchTerm($query);
        }else {
            // If no search query provided, fetch all tournois
            $match_entiti = $matchRepository->findAll();
        }

        $match_entiti = $paginator->paginate(
            $match_entiti, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('match_entity_back/index.html.twig', ['match_entities'=>$match_entiti

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

    #[Route('/new_back', name: 'app_match_entity_new_back', methods: ['GET', 'POST'])]
    public function new_back(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matchEntity = new MatchEntity();
        $form = $this->createForm(MatchEntityType::class, $matchEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($matchEntity);
            $entityManager->flush();

            return $this->redirectToRoute('app_match_entity_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match_entity_back/new.html.twig', [
            'match_entity' => $matchEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{idMatch}/show', name: 'app_match_entity_show', methods: ['GET'])]
    public function show(MatchEntity $matchEntity): Response
    {
        return $this->render('match_entity/show.html.twig', [
            'match_entity' => $matchEntity,
        ]);
    }

    #[Route('/{idMatch}/show_back', name: 'app_match_entity_show_back', methods: ['GET'])]
    public function show_back(MatchEntity $matchEntity): Response
    {
        return $this->render('match_entity_back/show.html.twig', [
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

    #[Route('/{idMatch}/edit_back', name: 'app_match_entity_edit_back', methods: ['GET', 'POST'])]
    public function edit_back(Request $request, MatchEntity $matchEntity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchEntityType::class, $matchEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_match_entity_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('match_entity_back/edit.html.twig', [
            'match_entity' => $matchEntity,
            'form' => $form,
        ]);
    }

    #[Route('/{idMatch}/delete', name: 'app_match_entity_delete', methods: ['POST'])]
    public function delete(Request $request, MatchEntity $matchEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matchEntity->getIdMatch(), $request->request->get('_token'))) {
            $entityManager->remove($matchEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_match_entity_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idMatch}/delete_back', name: 'app_match_entity_delete_back', methods: ['POST'])]
    public function delete_back(Request $request, MatchEntity $matchEntity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matchEntity->getIdMatch(), $request->request->get('_token'))) {
            $entityManager->remove($matchEntity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_match_entity_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
