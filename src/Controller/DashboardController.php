<?php

namespace App\Controller;

use App\Repository\DepenseRepository;
use App\Repository\DepenseTypeRepository;
use App\Repository\FactureRepository;
use App\Repository\LocalRepository;
use App\Repository\RentalRepository;
use App\Repository\TenantRepository;
use App\Utils\RapportItem;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Laminas\Json\Expr;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class DashboardController extends AbstractController
{
    private $localRepository;
    private $tenantRepository;
    private $billRepository;
    private $rentalRepository;
    private $chargeRepository;
    private $typeDepenseRepository;

    /**
     * DashboardController constructor.
     * @param $localRepository
     * @param $tenantRepository
     * @param $billRepository
     */
    public function __construct(DepenseTypeRepository $depenseTypeRepository, DepenseRepository $depenseRepository, RentalRepository $rentalRepository, LocalRepository $localRepository, TenantRepository $tenantRepository, FactureRepository $billRepository)
    {
        $this->localRepository = $localRepository;
        $this->tenantRepository = $tenantRepository;
        $this->billRepository = $billRepository;
        $this->rentalRepository = $rentalRepository;
        $this->chargeRepository = $depenseRepository;
        $this->typeDepenseRepository = $depenseTypeRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $listes_mois = ['Jaunary', 'Febraury', 'March', 'April', 'May',
            'June', 'July', 'August', 'September', 'October'
            , 'November', 'December'];
        $lib_somRentals = [];
        $lib_somRentalsAllrevenu = [];
        $lib_month = [];
        $currentday = new \DateTime("now");
        $currentyear = $currentday->format('Y');
        for ($i = 0; $i < sizeof($listes_mois); $i++) {
            $lib_month[] = $listes_mois[$i];
            $lib_somRentals[] = $this->rentalRepository->getAmountRentalByYearAndMonth($currentyear, $listes_mois[$i]);
            $lib_somRentalsAllrevenu[] = $this->rentalRepository->getAmountRentalByYearAndMonthAll($currentyear, $listes_mois[$i]);
        }
        $series = [
            [
                'name' => 'FCFA',
                'type' => 'column',
                'color' => '#4572A7',
                'yAxis' => 1,
                'data' => $lib_somRentals,
            ],
            [
                'name' => 'FCFA',
                'type' => 'spline',
                'color' => '#AA4643',
                // 'data' => 0,
                'data' => $lib_somRentalsAllrevenu,
            ],
        ];
        $yData = [
            [
                'labels' => [
                    'formatter' => new Expr('function () { return this.value + "Fcfa" }'),
                    'style' => ['color' => '#AA4643'],
                ],
                'title' => [
                    'text' => 'Total mensuel en FCFA',
                    'style' => ['color' => '#AA4643'],
                ],
                'opposite' => true,
            ],
            [
                'labels' => [
                    'formatter' => new Expr('function () { return this.value + "Fcfa" }'),
                    'style' => ['color' => '#4572A7'],
                ],
                'gridLineWidth' => 0,
                'title' => [
                    'text' => 'Revenus Mensuel en FCFA',
                    'style' => ['color' => '#4572A7'],
                ],
            ],
        ];

        $ob = new Highchart();
        $ob->chart->renderTo('container'); // The #id of the div where to render the chart
        $ob->chart->type('column');
        $ob->title->text('Montant encaissé pour cette année');
        $ob->xAxis->categories($lib_month);
        $ob->yAxis($yData);
        $ob->legend->enabled(false);
        $formatter = new Expr('function () {
                 var unit = {
                     "FCFA": "Fcfa",
                 }[this.series.name];
                 return this.x + ": <b>" + this.y + "</b> " + unit;
             }');
        $ob->tooltip->formatter($formatter);
        $ob->series($series);
        return $this->render('dashboard/index.html.twig', [
            'year' => $currentyear,
            'tenants' => $this->tenantRepository->count([]),
            'bills' => $this->billRepository->count([]),
            'locals' => $this->localRepository->count([]),
            'chart' => $ob,

        ]);
    }

    /**
     * @Route("/tauxoccupation", name="rapport_tauxoccupation")
     */
    public function tauxOccupation(): Response
    {
        return $this->render('dashboard/tauxoccupation.html.twig', [
            'controller_name' => 'DashboardController',
            'tenants' => $this->tenantRepository->count([]),
            'bills' => $this->billRepository->count([]),
            'locals' => $this->localRepository->count([]),
        ]);
    }

    /**
     * @Route("/rapport/depenses/", name="rapport_depense")
     */
    public function depenses(): Response
    {
        $listrapports = [];
        /**
         * Total somme deja percu
         */
        $rapportsomme = new RapportItem();
        $paid=$this->billRepository->sumAllPaid();
        $rapportsomme->setItem1("Total revenus");
        $rapportsomme->setItem2($paid);
        $rapportsomme->setItem3($this->billRepository->sumAllRemaning());
        $listrapports[] = $rapportsomme;
        $totaldepense=0.0;
        $totalrevenus=$paid;
        foreach ($this->typeDepenseRepository->findAll() as $type) {
            $rapportsomme = new RapportItem();
            $rapportsomme->setItem1($type->getLibelle());
            $td=$this->chargeRepository->sumAllPaid($type);
            $rapportsomme->setItem2($td);
            $rapportsomme->setItem3(0.0);
            $listrapports[] = $rapportsomme;
            $totaldepense+=$td;
        }
        return $this->render('dashboard/rapportdepense.html.twig', [
            'rapports' => $listrapports,
            'totaldepense'=>$totaldepense,
            'totalrevenus'=>$totalrevenus,
            'solde'=>$totalrevenus-$totaldepense
        ]);
    }
}
