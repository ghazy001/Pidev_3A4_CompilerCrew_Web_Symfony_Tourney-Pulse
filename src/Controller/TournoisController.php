<?php

namespace App\Controller;

use App\Entity\Tournois;
use App\Form\TournoisType;
use App\Repository\MatchRepository;
use App\Repository\TournoisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

#[Route('/tournois')]
class TournoisController extends AbstractController
{
    #[Route('/front', name: 'app_tournois_index', methods: ['GET'])]
    public function index(TournoisRepository $tournoisRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');
        $sortBy = $request->query->get('sortBy', 'name'); // Par défaut, trié par nom du tournoi
        $sortOrder = $request->query->get('sortOrder', 'DESC'); // Par défaut, trié par ordre décroissant

        // Récupération des tournois selon les critères de recherche et de tri
        if ($query) {
            $tournois = $tournoisRepository->findBySearchTerm($query);
        } elseif ($sortBy && $sortOrder) {
            // Si aucune requête de recherche fournie, récupérer tous les tournois avec tri
            $tournois = $tournoisRepository->findAllSorted($sortBy, $sortOrder);
        }

        $tournois = $paginator->paginate(
            $tournois, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('tournois/index.html.twig', [
            'tournois' => $tournois,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }



    #[Route('/back', name: 'app_tournois_back_index', methods: ['GET'])]
    public function index_back(TournoisRepository $tournoisRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('query');

        if ($query) {
            $tournoi = $tournoisRepository->findBySearchTerm($query);
        }else {
            // If no search query provided, fetch all tournois
            $tournoi = $tournoisRepository->findAll();
        }

        $tournoi = $paginator->paginate(
            $tournoi, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('tournois_back/index.html.twig', ['tournois'=>$tournoi

        ]);
    }

    #[Route('/new', name: 'app_tournois_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournois();
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournois/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/new_back', name: 'app_tournois_back_new', methods: ['GET', 'POST'])]
    public function new_back(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tournoi = new Tournois();
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tournoi);
            $entityManager->flush();

            return $this->redirectToRoute('app_tournois_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournois_back/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{idTournois}/show', name: 'app_tournois_show', methods: ['GET'])]
    public function show(Tournois $tournoi): Response
    {
        return $this->render('tournois/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/{idTournois}/show_back', name: 'app_tournois_back_show', methods: ['GET'])]
    public function show_back(Tournois $tournoi): Response
    {
        return $this->render('tournois_back/show.html.twig', [
            'tournoi' => $tournoi,
        ]);
    }

    #[Route('/{idTournois}/edit', name: 'app_tournois_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournois $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournois/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{idTournois}/edit_back', name: 'app_tournois_back_edit', methods: ['GET', 'POST'])]
    public function edit_back(Request $request, Tournois $tournoi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TournoisType::class, $tournoi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tournois_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournois_back/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{idTournois}/delete', name: 'app_tournois_delete', methods: ['POST'])]
    public function delete(Request $request, Tournois $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getIdTournois(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournois_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idTournois}/delete_back', name: 'app_tournois_back_delete', methods: ['POST'])]
    public function delete_back(Request $request, Tournois $tournoi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getIdTournois(), $request->request->get('_token'))) {
            $entityManager->remove($tournoi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tournois_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/listA', name: 'listA')]
    public function listTournois(TournoisRepository $tournoisRepository, Request $request): Response
    {
        $query = $request->query->get('query');

        if ($query) {
            $donnees = $tournoisRepository->findBySearchTerm($query);
        } else {
            // If no search query provided, fetch all tournois
            $donnees = $tournoisRepository->findAll();
        }

        return $this->render('tournois/search.html.twig', ['donnees' => $donnees]);
    }

//------------------------------stats-----------------
    #[Route('/stats', name: 'stats')]
    public function combinedStatsAndChart(TournoisRepository $tournoisRepository, MatchRepository $matchRepository, ChartBuilderInterface $chartBuilder): Response {
        // Fetch data for statistics

        $tournois = $tournoisRepository->Nbrtournois();
        $match = $matchRepository->Nbrmatchs();

        // Fetch data for pie chart
        $results = $tournoisRepository->countTournoisByStadium();

        $res = $matchRepository->countMatchByDate();

        // Prepare data for the chart
        $data = [['Stade', 'Nombre de Tournois']];
        foreach ($results as $result) {
            $data[] = [$result['stade'], $result['totalTournois']];
        }

        $dataForBarChart = [['Date Match', 'Nombre Match']];
        foreach ($res as $item) {
            $dataForBarChart[] = [$item['matchDate'], $item['matchCount']];
        }


        // Create PieChart instance
        $pieChart = new PieChart();

        // Set data to the chart
        $pieChart->getData()->setArrayToDataTable($data);

        // Set chart options
        $pieChart->getOptions()->setTitle('Nombre des Tournois dans le même Stade');
        $pieChart->getOptions()->setHeight(900);
        $pieChart->getOptions()->setWidth(1400);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        $piChart = new PieChart();

        // Set data to the chart
        $piChart->getData()->setArrayToDataTable($dataForBarChart);

        // Set chart options
        $piChart->getOptions()->setTitle('Nombre des Match dans la Même Date');
        $piChart->getOptions()->setHeight(900);
        $piChart->getOptions()->setWidth(1400);
        $piChart->getOptions()->getTitleTextStyle()->setBold(true);
        $piChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $piChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $piChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $piChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        // Render the view with the statistics and the chart
        return $this->render('tournois_back/statistique.html.twig', [
            'tournois' => $tournois,
            'match' => $match,
            'piechart' => $pieChart,
            'pichart' => $piChart
        ]);
    }

}

