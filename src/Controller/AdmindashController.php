<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Entity\Messages;
use App\Form\ReclamationType;
use App\Form\MessagesType;

class AdmindashController extends AbstractController
{
    #[Route('/admindash', name: 'admindash')]
    public function index(): Response
    {
        return $this->render('admindash/index.html.twig');
    }

    #[Route('/AAA', name: 'Rec', methods: ['GET'])]
    public function showRec(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('admindash/affichRec.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/Recnew', name: 'Rec_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $reclamation->setDateRec(new \DateTime());

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindash/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRec}/editRec', name: 'Rec_Edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindash/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{idRec}', name: 'Rec_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getIdRec(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('Rec', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idRec}', name: 'reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('admindash/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/msg/msg', name: 'SASA', methods: ['GET'])]
    public function SASA(EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager
            ->getRepository(Messages::class)
            ->findAll();

        return $this->render('admindash/affichMsg.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/new/msg', name: 'mess_new', methods: ['GET', 'POST'])]
    public function newM(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindash/newM.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }


    
    #[Route('/{id}/editM', name: 'mes_edit', methods: ['GET', 'POST'])]
    public function editM(Request $request, Messages $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admindash/editM.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/msg', name: 'mes_delete', methods: ['POST'])]
    public function deleteM(Request $request, Messages $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('SASA', [], Response::HTTP_SEE_OTHER);
    }


    
    #[Route('/{id}/mm', name: 'showmsg', methods: ['GET'])]
    public function showM(Messages $message): Response
    {
        return $this->render('admindash/showM.html.twig', [
            'message' => $message,
        ]);
    }










}
