<?php

namespace App\Controller;

use App\Entity\RentalContract;
use App\Entity\Tenant;
use App\Form\RentalContractType;
use App\Form\TenantType;
use App\Repository\LocalRepository;
use App\Repository\RentalContractRepository;
use App\Repository\TenantRepository;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tenant")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class TenantController extends AbstractController
{
    private $localRepository;
    private $tenantRepository;
    private $rentalContratRepository;
    private $dataTableFactory;

    /**
     * TenantController constructor.
     * @param $localRepository
     * @param $tenantRepository
     */
    public function __construct(RentalContractRepository $rentalContractRepository,DataTableFactory $dataTableFactory,LocalRepository $localRepository, TenantRepository $tenantRepository)
    {
        $this->localRepository = $localRepository;
        $this->tenantRepository = $tenantRepository;
        $this->rentalContratRepository=$rentalContractRepository;$this->dataTableFactory = $dataTableFactory;
    }

    /**
     * @Route("/", name="tenant_index", methods={"GET","POST"}, options={"expose"=true})
     */
    public function index(Request $request): Response
    {
        $table = $this->dataTableFactory->create()

            ->add('name', TextColumn::class, [
                'render' => function ($value, $context) {
                    return '<span>'.$value.'</span>';
                }
            ])
            ->add('situation', TextColumn::class)
            ->add('profession', TextColumn::class)
            ->add('phone', TextColumn::class)
            ->add('email', TextColumn::class)
            ->add('asContrat', TextColumn::class,[
                'label' => 'dt.columns.contrat',
                'orderable' => false,
                'visible'=>false,
                'render' => function ($value, $context) {

                    if ($value==0){
                        $url = $this->generateUrl('tenant_contrat', ['id' => $context->getId()]);
                        return '<a class="btn btn-success btn-sm"
                         href="'.$url.'" ><i class="fa fa-file-alt"></i></a>';
                    }else{
                        $url = $this->generateUrl('tenantcontratpdf', ['id' => $context->getId()]);
                        return '<a class="btn btn-warning btn-sm"  target="_blank"
                         href="'.$url.'" ><i class="fa fa-print"></i></a>';
                    }

                }
            ])
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'orderable' => false,
                'template' => 'tenant/button.html.twig',
                'render' => function ($value, $context) {
                    return $context;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Tenant::class,
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('tenant/index.html.twig', [
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/new", name="tenant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tenant = new Tenant();
        $tenant->setAsContrat(false);
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $imageFilename = $form['photo']->getData();
            if ($imageFilename) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($imageFilename->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFilename->guessExtension();

                try {
                    $imageFilename->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $tenant->setPhoto($newFilename);
            }
            $entityManager->persist($tenant);
            $entityManager->flush();
            $url = $this->generateUrl('tenant_contrat', ['id' => $tenant->getId()]);
            $this->addFlash('success', 'Operation executée avec success');
            return $this->redirect($url);
        }

        return $this->render('tenant/new.html.twig', [
            'tenant' => $tenant,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/contract/{id}", name="tenant_contrat", methods={"GET","POST"}, options={"expose"=true})
     */
    public function newRentalContrat(Tenant $tenant,Request $request): Response
    {
        $contrat = new RentalContract();
        $form = $this->createForm(RentalContractType::class, $contrat);
        $form->handleRequest($request);
        $locals= $this->localRepository->findBy(['status'=>'disponible']);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tenant->setAsContrat(true);
            $entityManager->persist($contrat);
            $entityManager->persist($tenant);
            $entityManager->flush();

            return $this->redirectToRoute('tenant_index');
        }

        return $this->render('tenant/newcontrat.html.twig', [
            'contrat' => $contrat,
            'form' => $form->createView(),
            'locals'=>$locals,
            'tenant'=>$tenant,
        ]);
    }

    /**
     * @Route("/{id}", name="tenant_show", methods={"GET"})
     */
    public function show(Tenant $tenant): Response
    {
        return $this->render('tenant/show.html.twig', [
            'tenant' => $tenant,
        ]);
    }

    /**
     * @Route("/{id}/editContrat", name="tenant_contrat_edit", methods={"GET","POST"})
     */
    public function editContrat(Request $request, Tenant $tenant): Response
    {
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tenant_index');
        }

        return $this->render('tenant/edit.html.twig', [
            'tenant' => $tenant,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit", name="tenant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tenant $tenant): Response
    {
        $form = $this->createForm(TenantType::class, $tenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tenant_index');
        }

        return $this->render('tenant/edit.html.twig', [
            'tenant' => $tenant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tenant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tenant $tenant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tenant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tenant_index');
    }
    /**
     * @Route("/ajax", name="tenant_save_contrat", methods={"GET","POST"}, options={"expose"=true})
     */
    public function saveContratAjax(Request $request): JsonResponse
    {
        $locals = $request->get('locals');
        $contrat=new RentalContract();
        $contrat->setAmount($request->get('amount'));
        $tenant=$this->tenantRepository->find($request->get('tenant'));
        $contrat->setTenant($tenant);
        $contrat->setTypeRental($request->get('type'));
        $contrat->setStatus(true);
        $tenant->setAsContrat(true);
        for ($i=0;$i<sizeof($locals);$i++){
            $local=$this->localRepository->find($locals[$i]);
            $contrat->addLocal($local);
            $local->setStatus('occupe');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contrat);
        $entityManager->flush();
        return new JsonResponse($locals[0], 200);
    }
    /**
     * @Route("/pdf/print-pdf", defaults={}, name="tenantpdf")
     */
    public function printMpdf()
    {
        $html = $this->renderView('pdf/tenant_list.html.twig', [
            'title' => 'liste des products',
            'tenants' => $this->tenantRepository->findAll(),
            'path' => 'assets/img/logo.png',
        ]);
        try {
            $mpdf = new Mpdf([
                // 'mode' => 'c',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 5,
                'margin_bottom' => 25,
                'margin_header' => 16,
                'margin_footer' => 13,
            ]);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } catch (MpdfException $e) {
            // echo $e->getMessage();
            dump($e->getMessage());
        }
    }
    /**
     * @Route("/pdf/print-contrat/{id}", defaults={}, name="tenantcontratpdf")
     */
    public function printContratMpdf(Request $request,Tenant $tenant)
    {
        $html = $this->renderView('pdf/tenant_contrat.html.twig', [
            'title' => 'liste des products',
            'contrat' => $this->rentalContratRepository->findOneBy(['tenant'=>$tenant,'status'=>true]),
            'path' => 'images/logo.png',
        ]);
        try {
            $mpdf = new Mpdf([
                // 'mode' => 'c',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 5,
                'margin_bottom' => 25,
                'margin_header' => 16,
                'margin_footer' => 13,
            ]);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } catch (MpdfException $e) {
            // echo $e->getMessage();
            dump($e->getMessage());
        }
    }
}
