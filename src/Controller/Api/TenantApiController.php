<?php


namespace App\Controller\Api;


use App\Entity\Local;
use App\Entity\RentalContract;
use App\Entity\Tenant;
use App\Form\Api\LocalApiType;
use App\Form\Api\RentalContractApiType;
use App\Form\Api\TenantApiType;
use App\Repository\LocalRepository;
use App\Repository\RentalContractRepository;
use App\Repository\TenantRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TenantApiController extends BaseApiController
{

    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;
    protected $tenantRepository;
    protected $userRepository;
    protected $contratRepository;
    protected $localRepository;

    /**
     * LocalApiController constructor.
     * @param NormalizerInterface $errorNormalizer
     * @param $tenantRepository
     * @param $userRepository
     * @param $contratRepository
     */
    public function __construct(LocalRepository $localRepository, NormalizerInterface $errorNormalizer, RentalContractRepository $contractRepository, UserRepository $userRepository, TenantRepository $tenantRepository)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
        $this->contratRepository = $contractRepository;
        $this->localRepository = $localRepository;
    }

    /**
     * Creates new Job resource
     *
     * @Rest\Post("/api/tenant/create")
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        try {
            $form = $this->createForm(TenantApiType::class, new Tenant());
            $data = $this->getJsonDecodedFromRequest($request);
            $form->submit($data);
            if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);
                return View::create($response, Response::HTTP_BAD_REQUEST);
            }
            $this->saveServer($form->getNormData());
            return View::create($form->getNormData(), Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return View::create($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * List all resources
     *
     * @Rest\Get("/api/tenant/list")
     * @param Request $request
     *
     * @return View
     */
    public function list(Request $request)
    {
        $array = [];
        $idx = 0;
        try {
            $lists = $this->tenantRepository->findBy([]);
            foreach ($lists as $local) {
                $temp = [
                    'id' => $local->getId(),
                    'name' => $local->getName(),
                    'phone' => $local->getPhone(),
                    'email' => $local->getEmail(),
                    'cni' => $local->getCni(),
                    'nationality' => $local->getNationality(),
                    'profession' => $local->getProfession(),
                    'situation' => $local->getSituation(),
                    'numberchild' => $local->getNumberChild(),
                ];
                $array[$idx++] = $temp;
            }

            return View::create($array, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * List all resources
     *
     * @Rest\Get("/api/tenant/{id}")
     * @param Request $request
     *
     * @return View
     */
    public function getOne(Request $request)
    {
        try {
            $tenant = $this->tenantRepository->find($request->get('id'));

                $temp = [
                    'id' => $tenant->getId(),
                    'name' => $tenant->getName(),
                    'phone' => $tenant->getPhone(),
                    'email' => $tenant->getEmail(),
                    'cni' => $tenant->getCni(),
                    'nationality' => $tenant->getNationality(),
                    'profession' => $tenant->getProfession(),
                    'situation' => $tenant->getSituation(),
                    'numberchild' => $tenant->getNumberChild(),
                ];
             //   $array[$idx++] = $temp;


            return View::create($temp, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    protected function saveServer(Tenant $tenant)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tenant);
        $entityManager->flush();
    }

    /**
     * Creates new Job resource
     *
     * @Rest\Post("/api/tenant/create_contrat")
     * @param Request $request
     *
     * @return View
     */
    public function postContractAction(Request $request)
    {
        try {
            $form = $this->createForm(RentalContractApiType::class, new RentalContract());
            $data = $this->getJsonDecodedFromRequest($request);
            $form->submit($data);
            if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);
                return View::create($response, Response::HTTP_BAD_REQUEST);
            }
            //$this->saveServer($form->getNormData());
            return View::create($data, Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return View::create($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}