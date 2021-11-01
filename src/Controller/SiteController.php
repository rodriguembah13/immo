<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\SiteElement;
use App\Form\SiteElementType;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use App\Utils\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/site")
 */
class SiteController extends AbstractController
{
    /**
     * @Route("/", name="site_index", methods={"GET"})
     */
    public function index(SiteRepository $siteRepository): Response
    {
        return $this->render('site/index.html.twig', [
            'sites' => $siteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="site_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $site->setCode("azerty");
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_show', ['id'=>$site->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/new.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/newsite/{id}", name="site_element_new", methods={"GET","POST"})
     */
    public function newElement(Request $request,Site $site): Response
    {
        $siteElement = new SiteElement();
        $form = $this->createForm(SiteElementType::class, $siteElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $number=$form['number']->getData();
            $str="";
            if ($siteElement->getConsitance() == Constants::MAGASIN){
                $str="Mag_";
            }elseif ($siteElement->getConsitance() == Constants::BOUTIQUE){
                $str="Boutiq_";
            }elseif ($siteElement->getConsitance() == Constants::APPARTEMENT){
                $str="Appart_";
            }elseif ($siteElement->getConsitance() == Constants::STUDIO){
                $str="Stud_";
            }else{
                $str="Chamb_";
            }
          /*  if ($siteElement->getPosition()==0){
                $siteElement->setPosition('Ras de chaussée');
            }else{
                $siteElement->setPosition('Etage_'.$siteElement->getPosition());
            }*/
            $siteElement->setNumber($str.$number);
            $siteElement->setCreatedAt(new \DateTime("now"));
            $siteElement->setUpdatedAt(new \DateTime("now"));
            $siteElement->setSite($site);
            $entityManager->persist($siteElement);
            $entityManager->flush();

            return $this->redirectToRoute('site_show', ["id"=>$site->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/newelement.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="site_show", methods={"GET"})
     */
    public function show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'site' => $site,
            'elements'=>$site->getSiteElements()
        ]);
    }
    /**
     * @Route("/{id}/{element}", name="site_element_page", methods={"GET"})
     */
    public function elementPage(Site $site,SiteElement $element): Response
    {
        return $this->render('site/elementpage.html.twig', [
            'site' => $site,
            'element'=>$element
        ]);
    }
    /**
     * @Route("/{id}/edit", name="site_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Site $site): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="site_delete", methods={"POST"})
     */
    public function delete(Request $request, Site $site): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
    }
}
