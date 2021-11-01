<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\FactureItem;
use App\Entity\Local;
use App\Entity\RentalContract;
use App\Entity\Tenant;
use App\Form\FactureType;
use App\Repository\ConfigurationRepository;
use App\Repository\FactureRepository;
use App\Repository\RentalRepository;
use App\Repository\TenantRepository;
use App\Utils\searchForm;
use Doctrine\ORM\QueryBuilder;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facture")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class FactureController extends AbstractController
{
    private $configurationRepository;
    private $factureRepository;
    private $rentalRepository;
    private $tenantRepository;
    private $dataTableFactory;
    /**
     * FactureController constructor.
     * @param $configurationRepository
     * @param $factureRepository
     */
    public function __construct(RentalRepository $rentalRepository,DataTableFactory $dataTableFactory, TenantRepository $tenantRepository,ConfigurationRepository $configurationRepository, FactureRepository $factureRepository)
    {
        $this->configurationRepository = $configurationRepository;
        $this->factureRepository = $factureRepository;
        $this->rentalRepository = $rentalRepository;
        $this->tenantRepository = $tenantRepository;$this->dataTableFactory = $dataTableFactory;
    }

    /**
     * @Route("/", name="facture_index", methods={"GET","POST"})
     */
    public function index( Request $request): Response
    {
        $table = $this->dataTableFactory->create()

            ->add('tenant', TextColumn::class, [
                'field'=>'tenant.name'
            ])
            ->add('status', TextColumn::class)
            ->add('amount', TextColumn::class)
            ->add('amountDue', TextColumn::class)
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'template' => 'facture/button.html.twig',
                'render' => function ($value, $context) {
                    return $context;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Facture::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('e')
                        ->from(Facture::class, 'e')
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
        return $this->render('facture/index.html.twig', [
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/datatbles", name="facture_datatables_index", methods={"GET","POST"})
     */
    public function indexdatable(Request $request,FactureRepository $factureRepository, DataTableFactory $dataTableFactory): Response
    {
        $table2 = $dataTableFactory->create()
            ->add('firstName', TextColumn::class)
            ->add('lastName', TextColumn::class)
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
            ])
            ->handleRequest($request);
        $table = $dataTableFactory->create()
            ->add('id', NumberColumn::class, [ "searchable" => true])
            ->add('amount', NumberColumn::class,['orderable'=>true])
           // ->add('company', TextColumn::class, ['field' => 'company.name'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => FactureItem::class,
              'query'=>function(QueryBuilder $builder){
                  $builder
                      ->select('e')
                      //->addSelect('c')
                      ->from(FactureItem::class, 'e')
                      //->leftJoin('e.createdBy', 'c')
                  ;
              }
            ])
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('facture/datatables.html.twig', [
            'paginator' => $factureRepository->findAll(),
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/new", name="facture_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
          /*  $facture->setAmountDue(0);
        $facture->setTotal(0);*/
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $alls= $facture->getTenant()->getRentals();
            $rentals=[];
            $som=$facture->getAmount();
            $totalA=0;
            foreach ($alls as  $rental){
                if ($rental->getAmountDue()>0 && $rental->isActive() && $rental->getStatus() != 'complete'){
                    $totalA+=$rental->getAmountDue();
                    $rentals[]=$rental;
                }
            }
            $facture->setTotal($totalA);
            if ($facture->getAmount()>=$totalA){
                $facture->setAmountDue(0);
            }else{
                $facture->setAmountDue($totalA - $facture->getAmount());
            }
            foreach ($rentals as  $rental){
                if ($som>=0){
                    $factureItem=new FactureItem();
                    $factureItem->setRental($rental);
                    $factureItem->setFacture($facture);
                    if ($som>= $rental->getAmountDue()){
                        $factureItem->setAmount($rental->getAmountDue());
                        $factureItem->setAmountDue(0);
                        $rental->setStatus('complete');
                        $som-=$rental->getAmountDue();
                    }else{
                        $factureItem->setAmount($som);
                        $factureItem->setAmountDue($rental->getAmountDue()-$som);
                        $rental->setStatus('advanced');
                        $som=0;
                    }
                    $rental->setAmountDue($factureItem->getAmountDue());
                    $entityManager->persist($factureItem);
                }

            }

            $entityManager->persist($facture);
            $entityManager->flush();

            $url = $this->generateUrl('facture_show', ['id' => $facture->getId()]);
            $this->addFlash('success', 'Operation executée avec success');
            return $this->redirect($url);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/new/{id}/", name="facture_new_server", methods={"GET","POST"})
     */
    public function newServer(Request $request,Tenant $tenant,$amount): Response
    {
        $facture = new Facture();
            $entityManager = $this->getDoctrine()->getManager();
            $alls= $tenant->getRentals();
            $rentals=[];
            $som=$facture->getAmount();
            $totalA=0;
            foreach ($alls as  $rental){
                if ($rental->getAmountDue()>0 && $rental->isActive()){
                    $totalA+=$rental->getAmountDue();
                    $rentals[]=$rental;
                }
            }
            $facture->setTotal($totalA);
            if ($facture->getAmount()>=$totalA){
                $facture->setAmountDue(0);
            }else{
                $facture->setAmountDue($totalA - $facture->getAmount());
            }
            foreach ($rentals as  $rental){
                if ($som>=0){
                    $factureItem=new FactureItem();
                    $factureItem->setRental($rental);
                    $factureItem->setFacture($facture);
                    if ($som>= $rental->getAmountDue()){
                        $factureItem->setAmount($rental->getAmountDue());
                        $factureItem->setAmountDue(0);
                        $rental->setStatus('complete');
                        $som-=$rental->getAmountDue();
                    }else{
                        $factureItem->setAmount($som);
                        $factureItem->setAmountDue($rental->getAmountDue()-$som);
                        $rental->setStatus('advanced');
                        $som=0;
                    }
                    $rental->setAmountDue($factureItem->getAmountDue());
                    $entityManager->persist($factureItem);
                }

            }

            $entityManager->persist($facture);
            $entityManager->flush();

            $url = $this->generateUrl('facture_show', ['id' => $facture->getId()]);
            $this->addFlash('success', 'Operation executée avec success');
            return $this->redirect($url);
        }


    /**
     * @Route("/{id}", name="facture_show", methods={"GET"})
     */
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="facture_edit", methods={"GET","POST"}, options={"expose"=true})
     */
    public function edit(Request $request, Facture $facture): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('facture_index');
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="facture_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Facture $facture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_index');
    } /**
 * @Route("/get/amountdue", name="tenant_get_amountDue", methods={"GET","POST"}, options={"expose"=true})
 */
    public function getAmountdue(Request $request): JsonResponse
    {
        $tenant = $this->tenantRepository->find($request->get('tenant'));

        $amoutDue=$this->rentalRepository->getAmountDue($tenant);
        return new JsonResponse($amoutDue, 200);
    }
    /**
     * @Route("/searchfacture/ajax", name="search_facture_ajax", methods={"GET"})
     */
    public function searchAjax(Request $request): JsonResponse
    {
        $tenant = $request->get('item1');
       // $local = $request->get('item2');
        $debut = $request->get('item2');
        $fin = $request->get('item3');
        $jsonData = [];
        $idx = 0;
        $responseArray = [];

        $factures = $this->factureRepository->findByMultiparam($tenant, $debut, $fin);
        foreach ($factures as $depens) {
            $responseArray[] = [
                'id' => $depens->getId(),
                'tenant' => $depens->getTenant()->getName(),
                'amountDue' => $depens->getAmountDue(),
                'amount' => $depens->getAmount(),
                'datecreated' => $depens->getCreatedAt()->format('Y-m-d'),
            ];
        }
        return new JsonResponse($responseArray, 200);
    }
    /**
     * @Route("/{id}/print-facture-pdf2", defaults={}, name="printfacturepdf2",options={"expose"=true})
     */
    public function printRecu(Facture $facture)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('facture/pdf.html.twig', [
            'title' => 'Facture',
            'facture' => $facture,
            'configuration'=>$this->configurationRepository->findOneByLast(),
            'path' => 'images/logo_gp.png',
            'fature_items' => $facture->getFactureItems(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream('mypdf.pdf', [
            'Attachment' => false,
        ]);
    }
    /**
     * @Route("/{id}/print-facture-pdf", defaults={}, name="printfacturepdf",options={"expose"=true})
     */
    public function printMpdf(Facture $facture)
    {
        $html = $this->renderView('facture/pdf.html.twig', [
            'title' => 'liste des eleves',
            'facture' => $facture,
            'configuration'=>$this->configurationRepository->findOneByLast(),
            'path' => 'assets/img/logo.png',
        ]);
        try {
            $mpdf = new Mpdf([
                'mode' => 'c',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
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
