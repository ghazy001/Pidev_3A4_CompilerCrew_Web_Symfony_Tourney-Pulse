<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Entity\Messages;
use App\Form\ReclamationType;
use App\Form\MessagesType;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Form\FormError;

use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


#[Route('/adminRec')]
class AdmindashControllerReclamation extends AbstractController

{
    #[Route('/admindashReclamation', name: 'admindashReclamation')]
    public function index(): Response
    {
        return $this->render('admindashReclamation/index.html.twig');
    }

    #[Route('/AAA', name: 'Rec', methods: ['GET'])]
    public function showRec(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response

    {

        
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
            
            $reclamations = $paginator->paginate(
                $reclamations, /* query NOT result */
                $request->query->getInt('page', 1),
                5
            );

        return $this->render('admindashReclamation/affichRec.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/Recnew', name: 'Rec_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check for bad words
            $reclamationText = $reclamation->getReclamation();
            $hasProfanity = $this->BadWords($reclamationText); 
    
            // Check if the key 'contains_profanity' exists in the response
            if ($hasProfanity) {
                // Add error message to the form
                $form->get('reclamation')->addError(new FormError('Your reclamation contains inappropriate language.'));
                // Render the form with error messages
                return $this->renderForm('admindashReclamation/new.html.twig', [
                    'reclamation' => $reclamation,
                    'form' => $form,
                ]);
            }
    
            // Save reclamation if no bad words found
            $reclamation->setDateRec(new \DateTime());
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'La réclamation a été ajoutée avec succès !');

            return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindashReclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRec}/editRec', name: 'Rec_Edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindashReclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{idRec}', name: 'Rec_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getIdRec(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idRec}', name: 'reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('admindashReclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/msg/msg', name: 'SASA', methods: ['GET'])]
    public function SASA(EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager
            ->getRepository(Messages::class)
            ->findAll();

        return $this->render('admindashReclamation/affichMsg.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/new/msg', name: 'mess_new', methods: ['GET', 'POST'])]
    public function newM(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindashReclamation/newM.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }


    
    #[Route('/{id}/editM', name: 'mes_edit', methods: ['GET', 'POST'])]
    public function editM(Request $request, Messages $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindashReclamation/editM.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/msg', name: 'mes_delete', methods: ['POST'])]
    public function deleteM(Request $request, Messages $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
    }


    
    #[Route('/{id}/mm', name: 'showmsg', methods: ['GET'])]
    public function showM(Messages $message): Response
    {
        return $this->render('admindashReclamation/showM.html.twig', [
            'message' => $message,
        ]);
    }






    
    private function BadWords($text)
    {
        // API endpoint URL
        $url = 'https://api.api-ninjas.com/v1/profanityfilter';
    
        // Your API key
        $apiKey = 'peTUeliHK58PPnCnlPTUjQ==ioQcLLRKPDMEZdfA';
    
        // Create a HTTP client
        $client = HttpClient::create();
    
        // Send request
        $response = $client->request('GET', $url, [
            'query' => [
                'text' => $text
            ],
            'headers' => [
                'X-Api-Key' => $apiKey
            ]
        ]);
    
        // Get response content
        $content = $response->getContent();
    
        // Decode JSON response
        $result = json_decode($content, true);
    
        // Check if the text contains profanity
        $hasProfanity = isset($result['has_profanity']) ? $result['has_profanity'] : false;
    
        return $hasProfanity;
    }
    
    #[Route('/stats', name: 'stats')]
    public function combinedStatsAndChart(ReclamationRepository $ReclamationRepository, ChartBuilderInterface $chartBuilder): Response {
        // Fetch data for statistics
    
        $reclamation = $ReclamationRepository->nbrReclamation();
        
    
        // Fetch data for pie chart
        $results = $RepositoryRepository->countReclamationByEtat();
    
        
    
        // Prepare data for the chart
        $data = [['Reclamation', 'Nombre de Reclamtion']];
        foreach ($results as $result) {
            $data[] = [$result['etat'], $result['totalTournois']];
        }
    
    
    
        // Create PieChart instance
        $pieChart = new PieChart();
    
        // Set data to the chart
        $pieChart->getData()->setArrayToDataTable($data);
    
        // Set chart options
        $pieChart->getOptions()->setTitle('Nombre des Reclamation Traités');
        $pieChart->getOptions()->setHeight(900);
        $pieChart->getOptions()->setWidth(1400);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
    
    
        
        // Render the view with the statistics and the chart
        return $this->render('admindashReclamation/stat.html.twig', [
            'reclamation' => $reclamation,
           
            'piechart' => $pieChart,
          
        ]);
    }
    
    



}
