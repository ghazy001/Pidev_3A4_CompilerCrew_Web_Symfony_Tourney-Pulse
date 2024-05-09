<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;

#[Route('/status')]
class HomeControllerUser extends AbstractController
{
    #[Route('/status', name: 'app_home_user')]
    public function index(): Response
    {
        return $this->render('status/status.html.twig', [
        ]);
    }
}
