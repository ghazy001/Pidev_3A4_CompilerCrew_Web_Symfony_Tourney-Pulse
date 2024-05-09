<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Stade;
use App\Entity\Equipe;
use Endroid\QrCode\QrCode;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Endroid\QrCode\Writer\PngWriter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/index_stade', name: 'app_reservation_index_stade', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request
,PaginatorInterface $paginator
): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();
            $reservations = $paginator->paginate(
                $reservations, /* query NOT result */
                $request->query->getInt('page', 1),
                3
            );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    #[Route('/rev', name: 'app_res', methods: ['GET'])]
    public function res(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('reservation/_back_rev.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();  // Initial flush to get ID

            $qrCode = QrCode::create('Reservation Details: ' . $reservation->getId())
                ->setSize(300)
                ->setMargin(10);
            $qrCodeData = $qrCode->writeDataUri();
            $reservation->setQrCodeBase64($qrCodeData);

            $entityManager->persist($reservation);
            $entityManager->flush();  // Second flush to save QR code

            $pdfContent = $this->renderView('reservation/reservation_pdf.html.twig', [
                'reservation' => $reservation,
                'qrCode' => $qrCodeData,
            ]);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($pdfContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $response = new Response($pdfOutput);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'inline; filename="reservation_' . $reservation->getId() . '.pdf"');

            return $response;
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('/stadeFront/{stadeId}/new', name: 'app_stade_reservation_new', methods: ['GET', 'POST'])]
    public function newForStade(Request $request, EntityManagerInterface $entityManager, $stadeId): Response
    {
        $stade = $entityManager->getRepository(Stade::class)->find($stadeId);
        if (!$stade) {
            throw $this->createNotFoundException('No stade found for id ' . $stadeId);
        }

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setIdStade($stade);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();  // Initial flush to get ID
           
            $qrCode = new QrCode('Reservation Details:' . "\n" .
            'Stade: ' . $stade->getNom() . "\n" .
            
            'Première équipe: ' . $reservation->getIdPremiereequipe() . "\n" .
            'Deuxième équipe: ' . $reservation->getIdDeuxiemeequipe() . "\n" .
            'Organisateur: ' . $reservation->getIdOrganisateur());
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $dataUri = $result->getDataUri();
            $base64Data = explode(',', $dataUri)[1];
            $reservation->setQrCodeBase64($base64Data);

            $entityManager->persist($reservation);
            $entityManager->flush();

            $pdfContent = $this->renderView('reservation/reservation_pdf.html.twig', [
                'reservation' => $reservation,
                'qrCode' => $base64Data,
            ]);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($pdfContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            // Encode PDF output for data URL
            $pdfBase64 = base64_encode($pdfOutput);
            $pdfDataUrl = "data:application/pdf;base64," . $pdfBase64;

            $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Download your PDF</title>
    <script type="text/javascript">
        // Function to download the PDF automatically
        function downloadPDF() {
            var a = document.createElement("a");
            a.href = "{$pdfDataUrl}";
            a.download = "stade_reservation_{$stadeId}_{$reservation->getId()}.pdf";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            // Redirect after a short delay
            setTimeout(function(){
                window.location.href = "/reservation/index_stade";
            }, 500);  
        }
    </script>
</head>
<body onload="downloadPDF();">
    <p>If your download does not start automatically, <a href="{$pdfDataUrl}" download>click here</a>.</p>
    <p>You will be redirected back to the reservations list shortly.</p>
</body>
</html>
HTML;

            return new Response($html, Response::HTTP_OK, ['Content-Type' => 'text/html']);
        }



        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/sh', name: 'app_sh', methods: ['GET'])]
    public function showD(Reservation $reservation): Response
    {
        return $this->render('reservation/showD.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index_stade', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index_stade', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/del/{id}', name: 'app_del', methods: ['POST'])]
    public function del(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_res', [], Response::HTTP_SEE_OTHER);
    }


}