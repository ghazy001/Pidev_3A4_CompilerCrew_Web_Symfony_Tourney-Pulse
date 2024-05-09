<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/signup')]
class SignUpController extends AbstractController
{
    #[Route('/signup/signup', name: 'app_sign_up')]
    public function signUp(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password before setting it
            $newHashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $user->setPassword($newHashedPassword);
            $user->setRole('User');

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect the user after successful sign-up
            return $this->redirectToRoute('login');
        }

        return $this->render('sign_up/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
