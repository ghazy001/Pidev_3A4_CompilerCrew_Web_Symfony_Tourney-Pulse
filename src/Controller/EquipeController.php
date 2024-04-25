<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\User;
use App\Form\AffecterPlayersType;
use App\Form\EquipeType;
use App\Form\SheetsType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use HttpException;
use HttpRequest;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twilio\Rest\Client;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


/*
   *
   *
   * @author : ghazi saoudi
   *
   *
   *
   */

class EquipeController extends AbstractController
{


    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    #[Route('/equipe', name: 'equipe')]
    public function index(EquipeRepository $equipeRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees = $equipeRepository->findAll();

        $donnees = $paginator->paginate(
            $donnees, /* query NOT result */
            $request->query->getInt('page', 1),
            8
        );



        return $this->render('equipe/index.html.twig', [
            'donnees' => $donnees,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(ManagerRegistry $managerRegistry, Request $request): Response
    {

        $error = '';
        $eq = new Equipe();
        $form = $this->createForm(EquipeType::class, $eq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Retrieve the entity manager
            $entityManager = $managerRegistry->getManager();

            // Check if the equipe already exists by name
            $nom = $eq->getNom();
            $existingEquipe = $entityManager->getRepository(Equipe::class)->findOneBy(['nom' => $nom]);
            if ($existingEquipe) {
                $error = 'Equipe with this name already exists.';
            } else {


                // Check if the checkbox is checked
                $sendSms = $request->request->get('send_sms');

                if ($sendSms) {
                    $this->SendSms();
                }
                //-------------------------------------
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    // Use the original filename of the uploaded file
                    $originalFilename = $imageFile->getClientOriginalName();

                    // Move the file to the desired directory
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $originalFilename
                        );
                    } catch (FileException $e) {
                        // Handle file upload error
                    }

                    // Store the file name in the 'image' property of the Equipe entity
                    $eq->setImage($originalFilename);
                }

                $em = $managerRegistry->getManager();
                $em->persist($eq);
                $em->flush();

                return $this->redirectToRoute('equipe');
            }

        }
        return $this->render('equipe/add.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }


    #[Route('/affecter', name: 'affecter', methods: ['GET', 'POST'])]
    public function affecter(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AffecterPlayersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $equipeId = $data['equipe'];
            $userId = $data['user'];

            $equipe = $entityManager->getRepository(Equipe::class)->find($equipeId);
            $user = $entityManager->getRepository(User::class)->find($userId);

            if ($equipe && $user) {
                // Check if the equipe already has 4 users
                if (count($equipe->getUsers()) < 4) {
                    try {
                        $user->setEquipe($equipe);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $this->addFlash('success', 'Player affected successfully.');
                        return $this->redirectToRoute('player'); // Redirect to home after success
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'An error occurred while affecting player to team.');
                    }
                } else {
                    $this->addFlash('error', 'The team already has 4 players.');
                }
            } else {
                $this->addFlash('error', 'Invalid user or team.');
            }
        }

        return $this->render('equipe/affecter_players.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('/player', name: 'player')]
    public function yourAction(EquipeRepository $equipeRepository): Response
    {
        $equipeList = $equipeRepository->recuperer();

        return $this->render('equipe/players.html.twig', [
            'equipeList' => $equipeList,
        ]);
    }


    public function SendSms()
    {
        $sid = "AC4d1d41f7d616d4a60ac222a861f9a695";
        $token = "";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create("+21626864405", // to
                array(
                    "from" => "+17816337025",
                    "body" => "your team has been added"
                )
            );

        print($message->sid);

    }


    //---------news----------------

    #[Route('/news', name: 'news')]
    public function news(Request $request): Response
    {
        $httpClient = HttpClient::create();

        try {
            $response = $httpClient->request('GET', 'https://heisenbug-premier-league-live-scores-v1.p.rapidapi.com/api/premierleague/table', [
                'headers' => [
                    'X-RapidAPI-Key' => '75127938d7msh51c5d5c4682346ep1183b9jsn567fbafe3aaf',
                    'X-RapidAPI-Host' => 'heisenbug-premier-league-live-scores-v1.p.rapidapi.com'
                ],
            ]);

            $content = $response->toArray();
            $records = $content['records'];

            return $this->render('equipe/news.html.twig', [
                'records' => $records,
            ]);
        } catch (\Exception $e) {
            // Handle exception
            return $this->render('equipe/news.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Route('/newsScroll', name: 'newsscroll')]
    public function newsScroll(Request $request,HttpClientInterface $client): Response
    {
        // Make a request to the Premier League API
        $response = $this->client->request('GET', 'https://heisenbug-premier-league-live-scores-v1.p.rapidapi.com/api/premierleague', [
            'headers' => [
                'X-RapidAPI-Key' => '75127938d7msh51c5d5c4682346ep1183b9jsn567fbafe3aaf',
                'X-RapidAPI-Host' => 'heisenbug-premier-league-live-scores-v1.p.rapidapi.com',
            ],
            'query' => [
                'matchday' => '1', // Adjust the matchday if needed
            ],
        ]);

        // Decode the JSON response
        $matches = $response->toArray();


        $rep = $client->request('GET', 'https://heisenbug-premier-league-live-scores-v1.p.rapidapi.com/api/premierleague/table/scorers', [
            'headers' => [
                'X-RapidAPI-Key' => '75127938d7msh51c5d5c4682346ep1183b9jsn567fbafe3aaf',
                'X-RapidAPI-Host' => 'heisenbug-premier-league-live-scores-v1.p.rapidapi.com',
            ],
        ]);

        // Decode the JSON response
        $scorers = $rep->toArray();

        // Render the matches using Twig
        return $this->render('equipe/newsScroll.html.twig', [
            'matches' => $matches['matches'],
            'scorers' => $scorers['scorers']// Assuming 'matches' is the key for the match data in the response
        ]);
    }


    //----------voice assisitant----------------

    #[Route('/test', name: 'test')]
    public function lol(EquipeRepository $equipeRepository): Response
    {


        $donne="hello world";


        return $this->render('equipe/test.html.twig', [
            'd'=>$donne

        ]);
    }


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
