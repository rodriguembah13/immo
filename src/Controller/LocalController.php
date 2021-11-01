<?php

namespace App\Controller;

use App\Entity\Local;
use App\Form\LocalEditType;
use App\Form\LocalType;
use App\Repository\LocalRepository;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/local")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class LocalController extends AbstractController
{
    private $dataTableFactory;

    /**
     * @param $dataTableFactory
     */
    public function __construct(DataTableFactory $dataTableFactory)
    {
        $this->dataTableFactory = $dataTableFactory;
    }

    /**
     * @Route("/", name="local_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $table = $this->dataTableFactory->create()
            ->add('consitance', TextColumn::class)
            ->add('numberRoon', TextColumn::class)
            ->add('number', TextColumn::class, [
                'label' => 'designation',
                'render' => function ($value, $context) {
                    return '<span>'.$value.'</span>';
                }
            ])
            ->add('status', TextColumn::class, [
                'render' => function ($value, $context) {
                if($value=="disponible"){
                    return '<span class="btn btn-sm btn-success">'.$value.'</span>';
                }elseif ($value=="travaux"){
                    return '<span class="btn btn-sm btn-warning">'.$value.'</span>';
                }else{
                    return '<span class="btn btn-sm btn-danger">'.$value.'</span>';
                }

                }])
            ->add('price', TextColumn::class)
            ->add('position', TextColumn::class)
            ->add('adresse', TextColumn::class)

            ->add('id', TwigColumn::class, [
                'className' => 'buttons',
                'label' => 'action','orderable' => false,
                'template' => 'local/button.html.twig',
                'render' => function ($value, $context) {
                    return $value;
                }])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Local::class,
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('local/index.html.twig', [
            'datatable' => $table
        ]);
    }
    /**
     * @Route("/new", name="local_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $local = new Local();
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $number=$form['number']->getData();
            $str="";
            if ($local->getNumberRoon()<2){
                $str="CH_";
            }elseif ($local->getNumberRoon()<=3){
                $str="ST_";
            }else{
                $str="AP_";
            }
            if ($local->getPosition()==0){
              $local->setPosition('Ras de chaussée');
            }else{
                $local->setPosition('Etage_'.$local->getPosition());
            }
            $local->setNumber($str.$number);
            $entityManager->persist($local);
            $entityManager->flush();

            return $this->redirectToRoute('local_index');
        }

        return $this->render('local/new.html.twig', [
            'local' => $local,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="local_show", methods={"GET"})
     */
    public function show(Local $local): Response
    {
        return $this->render('local/show.html.twig', [
            'local' => $local,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="local_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Local $local): Response
    {
        $form = $this->createForm(LocalEditType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('local_index');
        }

        return $this->render('local/edit.html.twig', [
            'local' => $local,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="local_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Local $local): Response
    {
        if ($this->isCsrfTokenValid('delete'.$local->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($local);
            $entityManager->flush();
        }

        return $this->redirectToRoute('local_index');
    }
}
