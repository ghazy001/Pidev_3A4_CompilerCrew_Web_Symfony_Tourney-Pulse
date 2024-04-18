<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LoginController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        // Retrieve email from the form
        $email = $request->request->get('email');

        // Check if email is null
        if ($email === null) {
            // Handle case where email is null
        }

        // Validate email format
        $emailError = $this->validateEmailFormat($email, $validator);
        if ($emailError !== null) {
            // Handle invalid email format error
        }

        // Check if email exists in the database
        $userRepository = $this->entityManager->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => $email]);
        if ($existingUser !== null) {
            // Handle existing email error
        }

        // Continue with your login logic if email is valid and not found in the database

        return $this->render('front/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    // Function to validate email format
    private function validateEmailFormat(?string $email, ValidatorInterface $validator): ?string
    {
        // Check if email is null
        if ($email === null) {
            return 'Email cannot be null';
        }

        // Validate email format
        $errors = $validator->validate($email, new Assert\Email());

        // If there are validation errors, return the first error message
        if (count($errors) > 0) {
            return $errors[0]->getMessage();
        }   

        // If email format is valid, return null
        return null;
    }
}
