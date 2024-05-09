<?php

namespace App\Controller;

use App\Entity\ImagesStade;
use App\Entity\Reservation;
use App\Entity\Stade;
use App\Form\StadeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/stade/stade')]
class StadeController extends AbstractController
{
    #[Route('/', name: 'app_stade_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request,
    PaginatorInterface $paginator): Response
    {
        $stades = $entityManager
            ->getRepository(Stade::class)
            ->findAll();
            $stades = $paginator->paginate(
                $stades, /* query NOT result */
                $request->query->getInt('page', 1),
                1
            );
        return $this->render('stade/index.html.twig', [
            'stades' => $stades,
        ]);
    }
    #[Route('/front', name: 'app_stade_indexFront', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager,Request $request,
    PaginatorInterface $paginator): Response
    {
        $stades = $entityManager
            ->getRepository(Stade::class)
            ->findAll();

            $stades = $paginator->paginate(
                $stades, /* query NOT result */
                $request->query->getInt('page', 1),
                3
            );

        return $this->render('stade/indexFront.html.twig', [
            'stades' => $stades,
        ]);
    }

    #[Route('/new', name: 'app_stade_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $stade = new Stade();
        $form = $this->createForm(StadeType::class, $stade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle multiple images
            $imagesFiles = $form->get('images')->getData(); // This will be an array of UploadedFile objects

            foreach ($imagesFiles as $imageFile) {
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('image_directory'),
                            $newFilename
                        );

                        // Create new ImagesStade entity and set properties
                        $imageEntity = new ImagesStade();
                        $imageEntity->setUrlImage($newFilename);
                        $imageEntity->setStade($stade); // Associate with the Stade entity

                        // Add the ImagesStade entity to the Stade entity
                        $stade->addImage($imageEntity);

                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                }
            }

            $entityManager->persist($stade);
            $entityManager->flush();

            return $this->redirectToRoute('app_stade_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stade/new.html.twig', [
            'stade' => $stade,
            'form' => $form,
        ]);
    }



    #[Route('/{id}', name: 'app_stade_show', methods: ['GET'])]
    public function show(Stade $stade): Response
    {
        return $this->render('stade/show.html.twig', [
            'stade' => $stade,
        ]);
    }
    #[Route('/', name: 'app_stade_aff', methods: ['GET'])]
    public function aff(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('stade/aff.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stade_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stade $stade, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(StadeType::class, $stade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesFiles = $form->get('images')->getData();

            foreach ($imagesFiles as $imageFile) {
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('image_directory'),
                            $newFilename
                        );

                        // Create new ImagesStade entity and set properties
                        $imageEntity = new ImagesStade();
                        $imageEntity->setUrlImage($newFilename);
                        $imageEntity->setStade($stade); // Associate with the Stade entity

                        // Add the ImagesStade entity to the Stade entity
                        $stade->addImage($imageEntity);

                    } catch (FileException $e) {
                    }
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_stade_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stade/edit.html.twig', [
            'stade' => $stade,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stade_delete', methods: ['POST'])]
    public function delete(Request $request, Stade $stade, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stade->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stade_index', [], Response::HTTP_SEE_OTHER);
    }
}
