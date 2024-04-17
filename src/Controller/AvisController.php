<?php

namespace App\Controller;

use App\Entity\Avisjoueur;
use App\Form\AvisType;
use App\Form\CompareType;
use App\Repository\AvisjoueurRepository;
use App\Repository\EquipeRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use HttpException;
use HttpRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis')]
    public function index(Request $request, AvisjoueurRepository $avisjoueurRepository): Response
    {
        // Check if the request is an AJAX request
        if ($request->isXmlHttpRequest()) {
            // Get the text to translate from the request
            $textToTranslate = $request->request->get('text');

            // Check if the text to translate is not empty
            if (empty($textToTranslate)) {
                return new Response('Text to translate is empty.', Response::HTTP_BAD_REQUEST);
            }

            // Make the RapidAPI call
            try {
                $response = $this->makeRapidAPICall($textToTranslate);
                $translatedText = $response['data']['translations'][0]['translatedText'];

                return new Response($translatedText);
            } catch (\Exception $e) {
                return new Response('Translation failed: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        $donnees = $avisjoueurRepository->TopPlayer();

        return $this->render('avis/index.html.twig', ['donnees' => $donnees]);
    }

    #[Route('/addA', name: 'addA')]
    public function addB(Request $req,ManagerRegistry $doctrine): Response
    {
        $Avis=new Avisjoueur();
        $form= $this->createForm(AvisType::class,$Avis);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $em =$doctrine->getManager();
            $em->persist($Avis);
            $em->flush();
            return $this->redirectToRoute('avis');
        }




        return $this->renderForm('avis/addAvis.html.twig', [
            'f' => $form,
        ]);
    }


    private function makeRapidAPICall(string $textToTranslate): array
    {
        $url = 'https://google-translate1.p.rapidapi.com/language/translate/v2';
        $headers = [
            'X-RapidAPI-Key' => '75127938d7msh51c5d5c4682346ep1183b9jsn567fbafe3aaf',
            'X-RapidAPI-Host' => 'google-translate1.p.rapidapi.com'
        ];
        $data = [
            'q' => $textToTranslate,
            'target' => 'fr',
            'source' => 'en'
        ];

        // Prepare the request
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, array_keys($headers), $headers));

        // Execute the request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        // Close the request
        curl_close($curl);

        // Decode the response
        $decodedResponse = json_decode($response, true);

        // Check if response is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON received from API.');
        }

        return $decodedResponse;
    }



    //--------------- comparer -----------------------
    #[Route('/comp', name: 'comp')]
    public function compare(AvisjoueurRepository $avisjoueurRepository, Request $request,UserRepository $userRepository): Response
    {
        $form = $this->createForm(CompareType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $userId1 = $data['user1']->getId();
            $userId2 = $data['user2']->getId();

            // Fetch user objects
            $user1 = $userRepository->find($userId1);
            $user2 = $userRepository->find($userId2);

            $avgNoteUser1 = $avisjoueurRepository->getAverageNoteForUser($userId1);
            $avgNoteUser2 = $avisjoueurRepository->getAverageNoteForUser($userId2);

            // Compare the average notes
            $comparisonResult = '';
            if ($user1 && $user2) {
                if ($avgNoteUser1 > $avgNoteUser2) {
                    $comparisonResult = $user1->getUsername() . ' has a higher average note.';
                } elseif ($avgNoteUser1 < $avgNoteUser2) {
                    $comparisonResult = $user2->getUsername() . ' has a higher average note.';
                } else {
                    $comparisonResult = 'Both users have the same average note.';
                }
            } else {
                $comparisonResult = 'One or both users not found.';
            }

            return $this->render('avis/compare.html.twig', [
                'form' => $form->createView(),
                'comparisonResult' => $comparisonResult,
            ]);
        }

        return $this->render('avis/compare.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // editer avis


    function myMessage() {
        echo "Hello world!";
    }

    /*
    *
    *
    * @author : ghazi saoudi
    *
    *
    *
    */







}
