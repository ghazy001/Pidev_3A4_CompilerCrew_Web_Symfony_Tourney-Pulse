<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignupController extends AbstractController
{
    private $passwordEncoder;
    private $validator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @Route("/signup", name="app_signup")
     */
    public function index(Request $request): Response
    {
        // Check if the form is submitted
        if ($request->isMethod('POST')) {
            // Retrieve form data
            $formData['firstName'] = $request->request->get('fname');
            $formData['lastName'] = $request->request->get('lname');
            $formData['email'] = $request->request->get('email');
            $formData['username'] = $request->request->get('uname');
            $formData['phoneNumber'] = $request->request->get('number');

            // Validate confirm password
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Password and confirm password do not match.');
            }

            // Check if email, username, or phone number already exists in the database
            $entityManager = $this->getDoctrine()->getManager();
            $existingUser = $entityManager->getRepository(User::class)->findOneBy([
                'email' => $formData['email'],
                'username' => $formData['username'],
                'number' => $formData['phoneNumber']
            ]);

            if ($existingUser) {
                if ($existingUser->getEmail() === $formData['email']) {
                    $this->addFlash('error', 'Email is already in use.');
                }
                if ($existingUser->getUsername() === $formData['username']) {
                    $this->addFlash('error', 'Username is already in use.');
                }
                if ($existingUser->getNumber() === $formData['phoneNumber']) {
                    $this->addFlash('error', 'Phone number is already in use.');
                }
                return $this->render('front/signup.html.twig');
            }

            // Create a new User entity object
            $user = new User();
            $user->setFirstName($formData['firstName']);
            $user->setLastName($formData['lastName']);
            $user->setEmail($formData['email']);
            $user->setUsername($formData['username']);
            $user->setNumber($formData['phoneNumber']);
            $user->setPassword($password);
            $user->setRole('User');

            // Validate the user entity
            $errors = $this->validator->validate($user);

            // If there are validation errors, display them
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->render('front/signup.html.twig');
            }

            // Persist and flush the user entity to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to login page or wherever appropriate
            return $this->redirectToRoute('app_login');
        }

        // If not submitted, render the signup form
        return $this->render('front/signup.html.twig');
    }
}
