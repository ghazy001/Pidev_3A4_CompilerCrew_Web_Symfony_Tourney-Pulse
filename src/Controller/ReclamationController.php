<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\PaginatorBundle\Pagination\PaginationInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Form\FormError;
use App\Service\SendGridService;
use App\Repository\ReclamationRepository;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/wesh/wesh', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new/ss/wesh', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
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
                return $this->renderForm('reclamation/new.html.twig', [
                    'reclamation' => $reclamation,
                    'form' => $form,
                ]);
            }

            // Save reclamation if no bad words found
            $reclamation->setDateRec(new \DateTime());
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRec}/edit/wesh', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRec}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdRec(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idRec}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{idRec}/traiter', name: 'app_reclamation_traiter', methods: ['POST'])]
    public function traiter(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        // Check if the form was submitted with the correct CSRF token
        if ($this->isCsrfTokenValid('traiter' . $reclamation->getIdRec(), $request->request->get('_token'))) {
            // Update the status of the reclamation
            $reclamation->setEtat('traité');
            // Persist changes to the database
            $entityManager->flush();
            $this->SendSms();
        }

        // Redirect back to the index page
        return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
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

    public function SendSms()
    {
        $sid = "secret";
        $token = "secret";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create("+21693038483", // to
                array(
                    "from" => "+13394991450",
                    "body" => "your reclamation on Tourney Pulse has been traited"
                )
            );

        print($message->sid);
    }
}
