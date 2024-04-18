<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupFormType;
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
        // Create an instance of the form
        $user = new User();
        $form = $this->createForm(SignupFormType::class, $user);

        // Handle form submission
        $form->handleRequest($request);

        // Check if the form is submitted
        if ($form->isSubmitted()) {
            // Check if the form is valid
            if ($form->isValid()) {
                // Encode the password
               
                $user->setRole('User');
                // Persist and flush the user entity to the database
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // Redirect to login page or wherever appropriate
                return $this->redirectToRoute('app_login');
            } else {
                return $this->render('front/signup.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        // Render the initial form
        return $this->render('front/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
