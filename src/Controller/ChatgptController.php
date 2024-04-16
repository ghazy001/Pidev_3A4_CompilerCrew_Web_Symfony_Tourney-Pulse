<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request; // Change this line
use OpenAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatgptController extends AbstractController
{
    #[Route('/chatgpt', name: 'app_chatgpt')]
    public function index(?string $question, ?string $response): Response
    {
        return $this->render('chatgpt/index.html.twig', [
            'question' => $question,
            'response' => $response
        ]);
    }

    #[Route('/chat', name: 'send_chat', methods: ["POST"])]
    // Modify the chat method in your controller
    public function chat(Request $request): Response
    {
        $question = $request->request->get('text');

        // Implementation du chat gpt

        $myApiKey ="";

        $client = OpenAI::client($myApiKey);
        try {
            $result = $client->completions()->create([
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => $question,
                'max_tokens' => 150, // Adjust the maximum number of tokens in the response
                'temperature' => 0.5, // Lowering the temperature for more deterministic responses
                'top_p' => 0.9 // Adjust the nucleus sampling probability to control diversity
            ]);

            // Extracting only the answer from the response
            $response = $result->choices[0]->text;

            // Remove the input question from the response
            $response = str_replace($question, '', $response);
            // Remove any leading or trailing whitespace
            $response = trim($response);

        } catch (\Exception $e) {
            // Log or handle the exception
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            // Optionally, return an error response
            return new Response('An error occurred while processing your request.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->forward('App\Controller\ChatgptController::index', [
            'question' => $question,
            'response' => $response
        ]);
    }

}
