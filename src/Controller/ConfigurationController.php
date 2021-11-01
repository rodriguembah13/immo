<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\Form\ConfigurationType;
use App\Repository\ConfigurationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/configuration")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ConfigurationController extends AbstractController
{
    private $configRepository;

    /**
     * ConfigurationController constructor.
     * @param $configRepository
     */
    public function __construct(ConfigurationRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/conf/rental", name="configuration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        $configuration= $this->configRepository->findOneByLast();
        if ($configuration ==null){
            $configuration=new Configuration();
        }
        $form = $this->createForm(ConfigurationType::class, $configuration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           // if ($configuration ==null){
               $this->getDoctrine()->getManager()->persist($configuration);
            //}
            $this->getDoctrine()->getManager()->flush();
            $url = $this->generateUrl('configuration_edit', ['id' => $configuration->getId()]);
            $this->addFlash('success', 'Operation executée avec success');
            return $this->redirect($url);
        }

        return $this->render('configuration/edit.html.twig', [
            'configuration' => $configuration,
            'form' => $form->createView(),
        ]);
    }

}
