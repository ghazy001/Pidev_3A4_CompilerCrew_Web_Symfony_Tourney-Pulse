<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\MarketPlace;
use App\Form\AvisType;
use App\Form\MarketPlaceType;
use App\Repository\MarketPlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Eckinox\PdfBundle\Exception\PdfGenerationException;
use Eckinox\PdfBundle\Pdf\FormatFactory;
use Eckinox\PdfBundle\Pdf\PdfGeneratorInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Knp\Snappy\Pdf;


#[Route('/marketplace')]
class MarketPlaceController extends AbstractController
{  private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }
    #[Route('/', name: 'app_market_place_indexback', methods: ['GET'])]
    public function index(MarketPlaceRepository $marketPlaceRepository): Response
    {
        $marketPlaces = $marketPlaceRepository->findAll();
        $averageRatings = [];

        foreach ($marketPlaces as $marketPlace) {
            $totalRating = 0;
            $numRatings = 0;
            foreach ($marketPlace->getAvis() as $avis) {
                $totalRating += $avis->getNote();
                $numRatings++;
            }
            $averageRating = $numRatings > 0 ? $totalRating / $numRatings : 0;
            $averageRatings[$marketPlace->getProdName() . '#' . $marketPlace->getId()] = $averageRating;
        }

        return $this->render('market_place/index.html.twig', [
            'market_places' => $marketPlaces,
            'average_ratings' => $averageRatings,
        ]);
    }
    #[Route('/front', name: 'app_market_place_index', methods: ['GET'])]
    public function indexfront(MarketPlaceRepository $marketPlaceRepository): Response
    {
        $marketPlaces = $marketPlaceRepository->findAll();

        foreach ($marketPlaces as $marketPlace) {

            $imageData = $marketPlace->getImage();
            $imageString = stream_get_contents($imageData);
            $marketPlace->setImage(base64_encode($imageString));
           
               
            
        }

        return $this->render('market_place/indexfront.html.twig', [
            'market_places' => $marketPlaces,
        ]);
    }

    #[Route('/new', name: 'app_market_place_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marketPlace = new MarketPlace();
        $form = $this->createForm(MarketPlaceType::class, $marketPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Image')->getData();

            if ($imageFile) {
                $imageContent = file_get_contents($imageFile->getPathname());
                $marketPlace->setImage($imageContent);
            }

            $entityManager->persist($marketPlace);
            $entityManager->flush();

            return $this->redirectToRoute('app_market_place_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('market_place/new.html.twig', [
            'market_place' => $marketPlace,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_market_place_show', methods: ['GET', 'POST'])]
    public function show(MarketPlace $marketPlace, Request $request, EntityManagerInterface $entityManager,Pdf $pdf): Response
    {
        $totalRating = 0;
        $numRatings = 0;
        foreach ($marketPlace->getAvis() as $avis) {
            $totalRating += $avis->getNote();
            $numRatings++;
        }
        $averageRating = $numRatings > 0 ? $totalRating / $numRatings : 0;

        // Create the review form
        $review = new Avis();
        $review->setDateAvis(new \DateTime()); // Set the review date to the current date/time
        $review->setMarketPlace($marketPlace); // Associate the review with the current product
        $form = $this->createForm(AvisType::class, $review);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the review to the database
            $entityManager->persist($review);
            $entityManager->flush();

            // Redirect back to the product details page
            return $this->redirectToRoute('app_market_place_show', ['id' => $marketPlace->getId()]);
        }

        // Encode the image as a base64 string
        $imageString = stream_get_contents($marketPlace->getImage());
        $imageBase64 = base64_encode($imageString);
        $qrCodeData = "Name: ". $marketPlace->getProdName(). "\nDescription: ". $marketPlace->getProdDescription(). "\nPrice: ". $marketPlace->getPrice();

        if ($request->query->get('pdf')) {
            // Render the HTML template as a PDF
            $html = $this->renderView('market_place/show.html.twig', [
                'market_place' => $marketPlace,
                'averageRating' => $averageRating,
                'image' => $imageBase64,
                'form' => $form->createView(),
                'qr_code_data' => $qrCodeData,
            ]);



            $pdf = $pdf->getOutputFromHtml($html);

            // Return the PDF as a response
            return new Response($pdf, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="product.pdf"',
            ]);
        }
        // Pass the average rating and the base64 encoded image to the template
        return $this->render('market_place/show.html.twig', [
            'market_place' => $marketPlace,
            'form' => $form->createView(),
            'averageRating' => $averageRating,
            'image' => $imageBase64,
            'qr_code_data' => $qrCodeData,

        ]);

    }
    #[Route('/qr-code/{data}', name: 'qr_code')]
public function qrCode(string $data): Response
{
    $qr_code = QrCode::create($data);
    $writer = new PngWriter();
    $result = $writer->write($qr_code);
    $response = new Response();
    $response->headers->set('Content-Type', $result->getMimeType());
    $response->setContent($result->getString());
    return $response;
}

    #[Route('/{id}/edit', name: 'app_market_place_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MarketPlace $marketPlace, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarketPlaceType::class, $marketPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_market_place_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('market_place/edit.html.twig', [
            'market_place' => $marketPlace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_market_place_delete', methods: ['POST'])]
    public function delete(Request $request, MarketPlace $marketPlace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marketPlace->getId(), $request->request->get('_token'))) {
            $entityManager->remove($marketPlace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_market_place_indexback', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/mail', name: 'app_market_place_mail', methods: ['GET', 'POST'])]
    public function mail(Request $request, MarketPlace $marketPlace, EntityManagerInterface $entityManager): Response
    {
        // Render the template with the product details
        return $this->render('market_place/mail.html.twig', [
            'marketPlace' => $marketPlace,
        ]);
    }
    #[Route('/{id}/mail-ajax', name: 'app_market_place_mail_ajax', methods: ['POST'])]
    public function mailAjax(Request $request, MarketPlace $marketPlace, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        // Create a Transport object
        $transport = Transport::fromDsn('smtp://azizhidri529@gmail.com:wguqfftwbapbvnlb@smtp.gmail.com:587');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new Email());

        // Set the "From address"
        $email->from('azizhidri529@gmail.com');

        // Get the "To address" from the AJAX request
        $email->to($request->request->get('to'));

        // Set "CC"
        # $email->cc('cc@example.com');
        // Set "BCC"
        # $email->bcc('bcc@example.com');
        // Set "Reply To"
        # $email->replyTo('fabien@example.com');
        // Set "Priority"
        # $email->priority(Email::PRIORITY_HIGH);

        // Set a "subject"
        $email->subject('Purchase Confirmation!');

        // Set the plain-text "Body"
        $email->text('The plain text version of the message.');

        // Set HTML "Body"
        $email->html('
        <h1>Product Details</h1>
        <ul>
            <li>Name: '.$marketPlace->getProdName().'</li>
            <li>Price: '.$marketPlace->getPrice().'</li>
            <li>Description: '.$marketPlace->getProdDescription().'</li>
        </ul>
    ');

        // Sending email with status
        try {
            // Send email
            $mailer->send($email);

            // Return a JSON response indicating success
            return new JsonResponse(['success' => true]);
        } catch (TransportExceptionInterface $e) {
            // Return a JSON response indicating failure
            return new JsonResponse(['success' => false]);
        }
    }


}
