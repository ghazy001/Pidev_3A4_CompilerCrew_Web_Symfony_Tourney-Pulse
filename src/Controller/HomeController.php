<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

class HomeController extends AbstractController
{
    #[Route('/status', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('status/status.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/random-fact', name: 'random_fact')]
    public function randomFact(): Response
    {
        // Create a GuzzleHTTP client
        $client = new Client();

        // Make a GET request to fetch country facts
        $response = $client->get('https://restcountries.com/v2/all');

        // Decode the JSON response
        $countries = json_decode($response->getBody(), true);

        // Select a random country
        $randomCountry = $countries[rand(0, count($countries) - 1)];

        // Render Twig template with random country fact
        return $this->render('randomfacts.html.twig', [
            'randomCountry' => $randomCountry
        ]);
    }
}
