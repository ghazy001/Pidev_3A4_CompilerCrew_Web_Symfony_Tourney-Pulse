<?php

namespace App\Controller;

use App\Form\AvisType;
use App\Form\EquipeType;
use App\Form\SheetsType;
use App\Repository\AvisjoueurRepository;
use App\Repository\EquipeRepository;
use App\Repository\UserRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


/*
   *
   *
   * @author : ghazi saoudi
   *
   *
   *
   */



class AdmindashController extends AbstractController
{
    #[Route('/admindash', name: 'admindash')]
    public function index(): Response
    {
        return $this->render('admindash/index.html.twig');
    }



    #[Route('/list', name: 'list')]
    public function list(Request $request, EquipeRepository $equipeRepository): Response
    {
        $year = $request->query->get('year');
        $order = $request->query->get('order');


        if ($year) {
            $donnees = $equipeRepository->findByYear($year); // Implement this method in your repository
        } else {
            $donnees = $equipeRepository->findAll();
        }
        if ($order === 'AtoZ') {
            $donnees = $equipeRepository->orderByname();
        } elseif ($order === 'date') {
            $donnees = $equipeRepository->orderBydate();
        }

        return $this->render('admindash/ListTeam.html.twig', ['donnees' => $donnees]);
    }




    #[Route('/listA', name: 'listA')]
    public function listAvis(AvisjoueurRepository $avisjoueurRepository,Request $request): Response
    {
        $query = $request->query->get('query');
        $donnees = $avisjoueurRepository->findBySearchQuery($query);
        return $this->render('admindash/ListAvis.html.twig',['donnees' => $donnees]);
    }




    #[Route('/suppE/{i}', name: 'suppE')]
    public function delete(EquipeRepository $repository ,$i,ManagerRegistry $doctrine): Response
    {
        // recuperer l'auteur a supp
        $equipe = $repository->find($i);
        // action de suppresuion
        $em=$doctrine->getManager();
        $em->remove($equipe);
        $em->flush();


        return $this->redirectToRoute('list');
    }


    #[Route('/suppA/{i}', name: 'suppA')]
    public function deleteAvis(AvisjoueurRepository $repository ,$i,ManagerRegistry $doctrine): Response
    {
        // recuperer l'auteur a supp
        $avis = $repository->find($i);
        // action de suppresuion
        $em=$doctrine->getManager();
        $em->remove($avis);
        $em->flush();


        return $this->redirectToRoute('listA');
    }

    #[Route('/edit_equipe/{id}', name: 'edit_equipe')]
    public function editEquipe(Request $request, $id,ManagerRegistry $doctrine,EquipeRepository $rep): Response
    {

        $var=$rep->find($id);

        $equipe=$var;

        $em =$doctrine->getManager();




        $form = $this->createForm(EquipeType::class, $equipe);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();


            return $this->redirectToRoute('list');
        }

        return $this->render('admindash/EditEquipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/edit_avis/{id}', name: 'edit_avis')]
    public function editAvis(Request $request, $id,ManagerRegistry $doctrine,AvisjoueurRepository $rep): Response
    {

        $var=$rep->find($id);

        $avis=$var;

        $em =$doctrine->getManager();




        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();


            return $this->redirectToRoute('listA');
        }

        return $this->render('admindash/EditAvis.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//------------------------------stats-----------------

    #[Route('/stats', name: 'stats')]
    public function combinedStatsAndChart(EquipeRepository $equipeRepository, AvisjoueurRepository $avisjoueurRepository, UserRepository $userRepository, ChartBuilderInterface $chartBuilder): Response {
        // Fetch data for statistics
        $equipe = $equipeRepository->Nbrequipe();
        $user = $userRepository->Nbruser();
        $avis = $avisjoueurRepository->Nbravis();

        // Fetch data for pie chart
        $results = $avisjoueurRepository->countAvisByJoueur();

        $res = $equipeRepository->countTeamsByYear();

        // Prepare data for the chart
        $data = [['Joueur', 'Nombre d\'avis']];
        foreach ($results as $result) {
            $data[] = [$result['firstname'], $result['totalAvis']];
        }

        $res = $equipeRepository->countTeamsByYear();
        $dataForBarChart = [['Year', 'Number of Teams']];
        foreach ($res as $item) {
            $dataForBarChart[] = [$item['year'], $item['teamCount']];
        }


        // Create PieChart instance
        $pieChart = new PieChart();

        // Set data to the chart
        $pieChart->getData()->setArrayToDataTable($data);

        // Set chart options
        $pieChart->getOptions()->setTitle('Nombre des Avis par joueur');
        $pieChart->getOptions()->setHeight(900);
        $pieChart->getOptions()->setWidth(1400);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);




        $barChart = new BarChart();

        // Set data to the chart
        $barChart->getData()->setArrayToDataTable($dataForBarChart);

        // Set chart options
        $barChart->getOptions()->setTitle('Nombre des equipe par année');
        $barChart->getOptions()->setHeight(800);
        $barChart->getOptions()->setWidth(1300);
        $barChart->getOptions()->getTitleTextStyle()->setBold(true);
        $barChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $barChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $barChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $barChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        // Render the view with the statistics and the chart
        return $this->render('admindash/stats.html.twig', [
            'equipe' => $equipe,
            'user' => $user,
            'avis' => $avis,
            'piechart' => $pieChart,
            'barchart' => $barChart
        ]);
    }





    //------------excel-------------



    protected function createSpreadsheet(EquipeRepository $equipeRepository)
    {
        $spreadsheet = new Spreadsheet();
        // Get active sheet - it is also possible to retrieve a specific sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Set cell name and merge cells
        $sheet->setCellValue('A1', 'Liste des Equipes')->mergeCells('A1:D1');

        // Set column names
        $columnNames = [
            'Nom de l\'équipe',
            'Date de création',
            'Logo',
        ];
        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            // Center text
            $sheet->getStyle($columnLetter.'1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // Text in bold
            $sheet->getStyle($columnLetter.'1')->getFont()->setBold(true);
            // Set background color
            $sheet->getStyle($columnLetter.'1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFA07A'); // Light Salmon
            // Set font color
            $sheet->getStyle($columnLetter.'1')->getFont()->getColor()->setARGB('FFFFFF'); // White
            // Border
            $sheet->getStyle($columnLetter.'1')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);
            // Autosize column
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            $columnLetter++;
        }

        // Fetch data from database
        $browserCharacteristics = $equipeRepository->findAll();

        // Add data for each row
        $i = 2; // Beginning row for active sheet
        foreach ($browserCharacteristics as $browserCharacteristic) {
            // Set cell values
            $sheet->setCellValue('A'.$i, $browserCharacteristic->getNom());
            $sheet->setCellValue('B'.$i, $browserCharacteristic->getDateCreation()->format('F j, Y'));

            // Insert logo (assuming the logo file path is stored in the database)
            $logoPath = $browserCharacteristic->getImage();
            if (!empty($logoPath) && file_exists($logoPath)) {
                $drawing = new Drawing();
                $drawing->setPath($logoPath);
                $drawing->setHeight(20); // Adjust height as needed
                $drawing->setWorksheet($sheet);
                $drawing->setCoordinates('C'.$i);
            }

            $i++;
        }

        return $spreadsheet;
    }



    #[Route('/export', name: 'export')]
    public function exportAction(Request $request, EquipeRepository $equipeRepository)
    {
        $form = $this->createForm(SheetsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $format = $data['format'];
            $filename = 'Listes_Equipes.'.$format;

            $spreadsheet = $this->createSpreadsheet($equipeRepository); // Pass EquipeRepository instance

            switch ($format) {
                case 'ods':
                    $contentType = 'application/vnd.oasis.opendocument.spreadsheet';
                    $writer = new Ods($spreadsheet);
                    break;
                case 'xlsx':
                    $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                    $writer = new Xlsx($spreadsheet);
                    break;
                case 'csv':
                    $contentType = 'text/csv';
                    $writer = new Csv($spreadsheet);
                    break;
                default:
                    return $this->render('AppBundle::export.html.twig', [
                        'form' => $form->createView(),
                    ]);
            }

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });

            return $response;


        }


        return $this->render('admindash/ExcelForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /*
     *
     *
     * @author : ghazi saoudi
     *
     *
     *
     */


    function myMessage() {
        echo "Hello world!";
    }









}
