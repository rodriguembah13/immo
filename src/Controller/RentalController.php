<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\FactureItem;
use App\Entity\Local;
use App\Entity\Rental;
use App\Entity\Tenant;
use App\Form\RentalType;
use App\Repository\ConfigurationRepository;
use App\Repository\RentalContractRepository;
use App\Repository\RentalRepository;
use App\Repository\TenantRepository;
use App\Utils\ChiffreToLetter;
use App\Utils\ItemDay;
use App\Utils\searchForm;
use Doctrine\ORM\QueryBuilder;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rental")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class RentalController extends AbstractController
{
    private $rentalRepository;
    private $tenantRepository;
    private $contratRepository;
    private $configurationRepository;
    private $dataTableFactory;
    /**
     * RentalController constructor.
     * @param $rentalRepository
     * @param $tenantRepository
     */
    public function __construct(ConfigurationRepository $configurationRepository,DataTableFactory $dataTableFactory,RentalContractRepository $rentalContractRepository, RentalRepository $rentalRepository, TenantRepository $tenantRepository)
    {
        $this->rentalRepository = $rentalRepository;
        $this->tenantRepository = $tenantRepository;
        $this->contratRepository = $rentalContractRepository;
        $this->configurationRepository=$configurationRepository;$this->dataTableFactory = $dataTableFactory;
    }


    public function index2(int $page, RentalRepository $rentalRepository): Response
    {
        $listes_mois = ['Jaunary' => 'Jaunary', 'Febraury' => 'Febraury', 'March' => 'March', 'April' => 'April', 'May' => 'May',
            'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October'
            , 'November' => 'November', 'December' => 'December'];
        $search=new searchForm();
        $form = $this->createFormBuilder($search)
            ->add('item1', EntityType::class, [
                'class' => Tenant::class,
                'multiple' => false,
                'placeholder' => 'rental.table.tenant',
                'translation_domain' => 'messages',
                'label' => 'rental.table.tenant',
                'required' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5,'data-type'=>'', 'data-live-search' => true],

            ])
            ->add('item2', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une annee',
                'choices' => ['2020' => '2000', ' 2021' => '2021', ' 2022' => '2022',
                ],
                'label' => 'rental.table.year',
                'translation_domain' => 'messages',
                'mapped' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ])->add('item3', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une annee',
                'choices' => $listes_mois,
                'label' => 'rental.table.month',
                'translation_domain' => 'messages',
                'mapped' => true,
                'attr' => ['class' => 'selectpicker', 'data-size' => 5, 'data-live-search' => true],
            ]) ->getForm();
        $latestPosts = $rentalRepository->findLatest($page);
        return $this->render('rental/index.html.twig', [
            'paginator' => $latestPosts,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/", name="rental_index", methods={"GET","POST"}, options={"expose"=true})
     */
    public function index(Request $request): Response
    {
        $table = $this->dataTableFactory->create()

            ->add('tenant', TextColumn::class, [
                'label' => 'name',
                'field'=>'tenant.name'
            ])
            ->add('rentallocal', TwigColumn::class, [
                'template' => 'rental/rentalContracts.html.twig',
                'render' => function ($value, $context) {

                    return $context;
                }
            ])
            ->add('status', TextColumn::class)
            ->add('amount', TextColumn::class)
            ->add('amountDue', TextColumn::class)
            ->add('year', TextColumn::class)
            ->add('month', TextColumn::class)
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'template' => 'rental/button.html.twig',
                'render' => function ($value, $context) {
                    return $value;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Rental::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('e')
                        ->from(Rental::class, 'e')
                        ->leftJoin('e.tenant','tenant')
                      //  ->leftJoin('tenant.rentalContracts','rentalContracts')
                        ->leftJoin('e.createdBy','createdBy')
                        ->orderBy('e.createdAt', 'DESC')
                    ;
                }
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('rental/index.html.twig', [
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/rental_relance/", name="rental_relance_index", methods={"GET","POST"}, options={"expose"=true})
     */
    public function listLocationrelance(Request $request): Response
    {
        $table = $this->dataTableFactory->create()

            ->add('tenant', TextColumn::class, [
                'field'=>'tenant.name'
            ])
             ->add('rentallocal', TwigColumn::class, [
                 'template' => 'rental/rentalContracts.html.twig',
                // 'field'=>'tenant.rentalContracts',
                 'render' => function ($value, $context) {

                     return $context;
                 }
             ])
            ->add('status', TextColumn::class)
            ->add('amount', TextColumn::class)
            ->add('amountDue', TextColumn::class)
            ->add('year', TextColumn::class)
            ->add('month', TextColumn::class)
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'template' => 'rental/buttonrelance.html.twig',
                'render' => function ($value, $context) {
                    return $value;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Rental::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('p')
                        ->from(Rental::class, 'p')
                        ->leftJoin('p.tenant','tenant')
                        ->where('p.endDate <= :now')
                        ->andWhere('p.active = 1')
                        ->andWhere('p.amountDue > 0.0')
                        ->orderBy('p.tenant', 'DESC')
                        ->setParameter('now', new \DateTime())
                    ;
                }
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('rental/rental_relance.html.twig', [
            'datatable' => $table
        ]);

    }
    /**
     * @Route("/new", name="rental_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rental = new Rental();
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);
        $listDays = [];
        $listes_mois = ['1' => 'Jaunary', '2' => 'Febraury', '3' => 'March', '4' => 'April', '5' => 'May',
            '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October'
            , '11' => 'November', '12' => 'December'];
        $number_days_inmonth = cal_days_in_month(CAL_GREGORIAN, 2, 2021);
        for ($i = 1; $i <= $number_days_inmonth; $i++) {
            $timestamp = mktime(0, 0, 0, 2, $i, 2021);
            $item = new ItemDay();
            $item->setNumber($i);
            $item->setName(date('D', $timestamp));
            $listDays[] = $item;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rental);
            $entityManager->flush();

            return $this->redirectToRoute('rental_index');
        }

        return $this->render('rental/new.html.twig', [
            'rental' => $rental,
            'tenants' => $this->tenantRepository->findAll(),
            'months' => $listes_mois,
            'days' => $listDays,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new_all", name="rental_make_all", methods={"GET","POST"})
     */
    public function new_make_All(Request $request): Response
    {
        $rental = new Rental();
        $form = $this->createFormBuilder($rental)
            ->add('year', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une annee',
                'choices' => ['2020' => '2000', ' 2021' => '2021', ' 2022' => '2022',
                ],
                'label' => 'rental.table.year',
                'translation_domain' => 'messages',
                'mapped' => true,
                'attr' => ['class' => 'select2', 'data-size' => 5, 'data-live-search' => true],
            ])/*->add('createdAt', DateType::class, [
                'label' => 'Date Limit to paid',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
            ])*/ ->getForm();

        // $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);
        $listDays = [];
        $listes_mois = ['1' => 'Jaunary', '2' => 'Febraury', '3' => 'March', '4' => 'April', '5' => 'May',
            '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October'
            , '11' => 'November', '12' => 'December'];
        $number_days_inmonth = cal_days_in_month(CAL_GREGORIAN, 2, 2021);
        for ($i = 1; $i <= $number_days_inmonth; $i++) {
            $timestamp = mktime(0, 0, 0, 2, $i, 2021);
            $item = new ItemDay();
            $item->setNumber($i);
            $item->setName(date('D', $timestamp));
            $listDays[] = $item;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rental);
            $entityManager->flush();

            return $this->redirectToRoute('rental_index');
        }

        return $this->render('rental/make_all.html.twig', [
            'rental' => $rental,
            'tenants' => $this->tenantRepository->findAll(),
            'months' => $listes_mois,
            'days' => $listDays,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rental_show", methods={"GET"})
     */
    public function show(Rental $rental): Response
    {
        return $this->render('rental/show.html.twig', [
            'rental' => $rental,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rental_edit", methods={"GET","POST"}, options={"expose"=true})
     */
    public function edit(Request $request, Rental $rental): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rental_index');
        }

        return $this->render('rental/edit.html.twig', [
            'rental' => $rental,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rental_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rental $rental): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rental->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rental);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rental_index');
    }

    /**
     * @Route("/ajax", name="rental_save_ajax", methods={"GET","POST"})
     */
    public function saveRendalAjax(Request $request): JsonResponse
    {
        $locals = $request->get('item');
        $type = $request->get('type');
        $tenant = $this->tenantRepository->find($request->get('tenant'));
        $amount = $request->get('amount');
        $year = $request->get('year');
        $entityManager = $this->getDoctrine()->getManager();
        for ($i = 0; $i < sizeof($locals); $i++) {
            $rendal_server = $this->rentalRepository->findBy(['month' => $locals[$i], 'year' => $year, 'tenant' => $tenant,'active'=>true]);
            if ($rendal_server == null) {
                $contrat = $this->contratRepository->findOneBySomeField($tenant);
                $rendal = new Rental();
                $rendal->setTenant($tenant);
                $rendal->setAmount($contrat->getAmount());
                $rendal->setAmountDue($contrat->getAmount());
                $rendal->setTypeRental($contrat->getTypeRental());
                $rendal->setCreatedBy($this->getUser());
                $rendal->setMonth($locals[$i]);
                $rendal->setYear($year);
                $rendal->setStatus('on-hold');
                $date_entree=$contrat->getCreatedAt()->getTimestamp();
                $date= new \DateTime();
                $d = (int) date('d', $date_entree);
                $dt_b_time=mktime(0,0,0,$rendal->getMonthInt($rendal->getMonth()),$d,(int)$year);
                if ($contrat->getTypeRental()=='mensual'){
                    $rendal->setBeginDate($date->setTimestamp($dt_b_time));
                    $interval= new \DateInterval('P1M');
                    $rendal->setEndDate($date->setTimestamp($dt_b_time)->add($interval));
                }
                $entityManager->persist($rendal);
                $entityManager->flush();
             //   $this->addFlash('success', 'Rendal for ' . $tenant->getName() . 'is created with success');
            }
            /*    if ($type == "nuitee") {
                    $rendal->setDay($locals[$i]);
                } else {
                    $rendal->setMonth($locals[$i]);
                    $rendal->setYear($year);
                }
                $entityManager->persist($rendal);*/
        }
        $this->generatefacture($tenant, $amount);

        $entityManager->flush();
        return new JsonResponse($d, 200);
    }

    public function generatefacture(Tenant $tenant, $amount)
    {
        $facture = new Facture();
        $entityManager = $this->getDoctrine()->getManager();
        $facture->setAmount($amount);
        $facture->setTenant($tenant);
        $alls = $tenant->getRentals();
        $rentals = [];
        $som = $amount;
        $totalA = 0;
        foreach ($alls as $rental) {
            if ($rental->getAmountDue() > 0) {
                $totalA += $rental->getAmountDue();
                $rentals[] = $rental;
            }
        }
        $facture->setTotal($totalA);
        if ($amount >= $totalA) {
            $facture->setAmountDue(0);
        } else {
            $facture->setAmountDue($totalA - $facture->getAmount());
        }
        foreach ($rentals as $rental) {
            if ($som >= 0) {
                $factureItem = new FactureItem();
                $factureItem->setRental($rental);
                $factureItem->setFacture($facture);
                if ($som >= $rental->getAmountDue()) {
                    $factureItem->setAmount($rental->getAmountDue());
                    $factureItem->setAmountDue(0);
                    $rental->setStatus('complete');
                    $som -= $rental->getAmountDue();
                } else {
                    $factureItem->setAmount($som);
                    $factureItem->setAmountDue($rental->getAmountDue() - $som);
                    $rental->setStatus('advanced');
                    $som = 0;
                }
                $rental->setAmountDue($factureItem->getAmountDue());
                $entityManager->persist($factureItem);
            }

        }
        $entityManager->persist($facture);
        $entityManager->flush();
    }

    /**
     * @Route("/all_ajax", name="rental_save_aa_ajax", methods={"GET","POST"})
     */
    public function saveRendalAjaxAll(Request $request): JsonResponse
    {
        $locals = $request->get('item');
        $year = $request->get('year');
        if ($year == null) {
            return new JsonResponse('year is not null', 404);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $tenants = $this->tenantRepository->findBy(['asContrat' => 'true']);
        for ($i = 0; $i < sizeof($locals); $i++) {
            foreach ($tenants as $tenant) {
                $rendal_server = $this->rentalRepository->findBy(['month' => $locals[$i], 'year' => $year, 'tenant' => $tenant,'active'=>true]);
                if ($rendal_server == null) {
                    $contrat = $this->contratRepository->findOneBySomeField($tenant);
                    $rendal = new Rental();
                    $rendal->setTenant($tenant);
                    $rendal->setAmount($contrat->getAmount());
                    $rendal->setAmountDue($contrat->getAmount());
                    $rendal->setTypeRental($contrat->getTypeRental());
                    $rendal->setCreatedBy($this->getUser());
                    $rendal->setMonth($locals[$i]);
                    $rendal->setYear($year);
                    $rendal->setStatus('on-hold');
                    $date_entree=$contrat->getCreatedAt()->getTimestamp();
                    $date= new \DateTime();
                    $d = (int) date('d', $date_entree);
                    $dt_b_time=mktime(0,0,0,$rendal->getMonthInt($rendal->getMonth()),$d,(int)$year);
                    if ($contrat->getTypeRental()=='mensual'){
                        $rendal->setBeginDate($date->setTimestamp($dt_b_time));
                        $interval= new \DateInterval('P1M');
                        $rendal->setEndDate($date->setTimestamp($dt_b_time)->add($interval));
                    }

                    $entityManager->persist($rendal);
                    $entityManager->flush();
                    $this->addFlash('success', 'Rendal for' . $tenant->getName() . 'is created with success');
                }
            }

        }

        /*   if ($type == "nuitee") {
               $rendal->setDay($locals[$i]);
           } else {

           }*/


        return new JsonResponse($locals, 200);
    }

    /**
     * @Route("/delete/ajax", name="rental_delete_ajax", methods={"GET"})
     */
    public function deleteAjax(Request $request): JsonResponse
    {
        $em = $this->rentalRepository->find($request->get('item_id'));
        try {
            $entityManager = $this->getDoctrine()->getManager();
            $em->setActive(false);
            $entityManager->persist($em);
            //$entityManager->remove($em);
            $entityManager->flush();
          //  $this->addFlash('success', 'operation effectue avec success');
        } catch (\Exception $exception) {
            //$this->addFlash('error', 'operation impossible' . $exception->getMessage());
        }

        return new JsonResponse('success', 200);
    }
    /**
     * @Route("/searchrental/ajax", name="search_rental_ajax", methods={"GET"})
     */
    public function searchAjax(Request $request): JsonResponse
    {
        $tenant=$request->get('item1');
        $year=$request->get('item2');
        $month=$request->get('item3');
        $jsonData = [];
        $idx = 0;
        $responseArray = [];

        $rentals = $this->rentalRepository->findByMultiparam($tenant, $year, $month);
        foreach ($rentals as $depens) {
            $locals= $depens->getTenant()->getRentalContracts();
            $locals_=null;
            foreach ($locals as $local){
                $locals_ .= " ".$local->getLocals()->first();
            }
            $responseArray[] = [
                'id' => $depens->getId(),
                'tenant' => $depens->getTenant()->getName(),
                'locals'=>$locals_,
                'year' => $depens->getYear(),
                'month' => $depens->getMonth(),
                'status' => $depens->getStatus(),
                'amount' => $depens->getAmount(),
                'amountDue' => $depens->getAmountDue(),
                'datecreated' => $depens->getCreatedAt()->format('Y-m-d'),
            ];
        }
        return new JsonResponse($responseArray, 200);
    }
    /**
     * @Route("/{id}/print-relance-pdf", defaults={}, name="printrelancepdf",options={"expose"=true})
     */
    public function printRelanceMpdf(Rental $rental)
    {
        $letter=new ChiffreToLetter();
        $html = $this->renderView('pdf/relanceRental.html.twig', [
            'title' => 'relances',
            'rental' => $rental,
            'letter'=>$letter->Conversion($rental->getAmountDue()),
            'configuration'=>$this->configurationRepository->findOneByLast(),
            'path' => 'assets/img/logo.png',
        ]);
        try {
            $mpdf = new Mpdf([
              //  'mode' => 'c',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 20,
                'margin_bottom' => 25,
                'margin_header' => 5,
                'margin_footer' => 13,
            ]);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } catch (MpdfException $mpdfException) {
            echo $mpdfException->getMessage();
        }
    }
}
