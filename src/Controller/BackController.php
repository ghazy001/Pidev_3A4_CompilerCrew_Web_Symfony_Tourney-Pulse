<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/back", name="app_back")
     */
    public function index(): Response
    {
        // Get the user repository
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        // Fetch all users from the database
        $users = $userRepository->findAll();

        return $this->render('back/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_back');
        }

        return $this->render('back/editpage.html.twig', [
            'form' => $form->createView(),
            'user' => $user, // Pass the user object to the template
        ]);
    }


    /**
     * @Route("/user/{id}/delete", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        // Optionally add a flash message to indicate success
        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('app_back');
    }
    /**
     * @Route("/user/{id}/ban", name="user_ban", methods={"GET", "POST"})
     */
    public function ban(Request $request, int $id): Response
    {
        // Get the user repository
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        
        // Find the user by ID
        $user = $userRepository->find($id);

        // Check if the user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Check if the form was submitted with the correct method
        if ($request->isMethod('POST')) {
            // Get the entity manager
            $entityManager = $this->getDoctrine()->getManager();

            // Set user as banned by setting the isBanned property to true
            $user->setIsBanned(true);

            // Persist the changes to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Optionally add a flash message to indicate success
            $this->addFlash('success', 'User banned successfully.');

            // Redirect back to the user's edit page
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        // If the form was not submitted with the correct method, redirect back to the user's edit page
        return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
    }

}
