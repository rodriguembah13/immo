<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\DepenseType;
use App\Entity\Local;
use App\Form\TypeDepenseType;
use App\Repository\DepenseRepository;
use App\Repository\DepenseTypeRepository;
use App\Utils\DateTime;
use App\Utils\searchForm;
use Doctrine\ORM\QueryBuilder;
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
 * @Route("/depense")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class DepenseController extends AbstractController
{
    private $depenseRepository;
    private $depenseTypeRepository;
    private $dataTableFactory;
    /**
     * DepenseController constructor.
     * @param $depenseRepository
     * @param $depenseTypeRepository
     */
    public function __construct(DepenseRepository $depenseRepository,DataTableFactory $dataTableFactory, DepenseTypeRepository $depenseTypeRepository)
    {
        $this->depenseRepository = $depenseRepository;
        $this->depenseTypeRepository = $depenseTypeRepository;$this->dataTableFactory = $dataTableFactory;
    }

    /**
     * @Route("/", name="depense_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $table = $this->dataTableFactory->create()

            ->add('libelle', TextColumn::class, [
            ])
            ->add('local', TextColumn::class, [
                 'field'=>'local.number'
             ])
            ->add('depensetype', TextColumn::class, [
                'label' => 'libelle',
                'field'=>'depensetype.libelle'
            ])
            ->add('amount', TextColumn::class)
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'template' => 'depense/button.html.twig',
                'render' => function ($value, $context) {
                    return $value;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Depense::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('e')
                        ->from(Depense::class, 'e')
                        ->leftJoin('e.local','local')
                          ->leftJoin('e.depenseType','depensetype')
                        ->leftJoin('e.createdBy','createdBy')
                        ->orderBy('e.createdAt', 'DESC')
                    ;
                }
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('depense/index.html.twig', [
            'datatable' => $table
        ]);

    }

    /**
     * @Route("/type", name="depense_type_index", methods={"GET","POST"})
     */
    public function indexType(Request $request): Response
    {
        $table = $this->dataTableFactory->create()


            ->add('libelle', TextColumn::class)
            ->add('description', TextColumn::class)
            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action',
                'template' => 'depense/buttontype.html.twig',
                'render' => function ($value, $context) {
                    return $value;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => DepenseType::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('e')
                        ->from(DepenseType::class, 'e')
                        ->orderBy('e.id', 'DESC')
                    ;
                }
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('depense/indextype.html.twig', [
            'datatable' => $table
        ]);
    }

    /**
     * @Route("/new", name="depense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $depense = new Depense();
        $form = $this->createForm(\App\Form\DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depense);
            $entityManager->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newtype", name="depense_type_new", methods={"GET","POST"})
     */
    public function newType(Request $request): Response
    {
        $depensetype = new \App\Entity\DepenseType();
        $form = $this->createForm(TypeDepenseType::class, $depensetype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depensetype);
            $entityManager->flush();

            return $this->redirectToRoute('depense_type_index');
        }

        return $this->render('depense/newtype.html.twig', [
            'depenseType' => $depensetype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_show", methods={"GET"})
     */
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="depense_edit", methods={"GET","POST"}, options={"expose"=true})
     */
    public function edit(Request $request, Depense $depense): Response
    {
        $form = $this->createForm(\App\Form\DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depense_index');
        }

        return $this->render('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editType", name="depense_edit_type", methods={"GET","POST"})
     */
    public function editType(Request $request, \App\Entity\DepenseType $depense): Response
    {
        $form = $this->createForm(TypeDepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depense_type_index');
        }

        return $this->render('depense/editType.html.twig', [
            'type' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index');
    }

    /**
     * @Route("/searchdepense/ajax", name="search_depense_ajax", methods={"GET"})
     */
    public function searchAjax(Request $request): JsonResponse
    {
        $type = $request->get('item1');
        $local = $request->get('item2');
        $debut = $request->get('item3');
        $fin = $request->get('item4');
        $jsonData = [];
        $idx = 0;
        $responseArray = [];

        $depenses = $this->depenseRepository->findByMultiparam($type, $local, $debut, $fin);
        foreach ($depenses as $depens) {
            $responseArray[] = [
                'id' => $depens->getId(),
                'libelle' => $depens->getLibelle(),
                'local' => $depens->getLocal()->getConsitance() . '_' . $depens->getLocal()->getNumber(),
                'type' => $depens->getDepenseType()->getLibelle(),
                'amount' => $depens->getAmount(),
                'dateAchat' => $depens->getDateAchat()->format('Y-m-d'),
                'datecreated' => $depens->getCreatedAt()->format('Y-m-d'),
            ];
        }
        return new JsonResponse($responseArray, 200);
    }
}
