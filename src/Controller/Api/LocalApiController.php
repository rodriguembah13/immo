<?php


namespace App\Controller\Api;


use App\Entity\Local;
use App\Entity\Rental;
use App\Form\Api\LocalApiType;
use App\Form\Api\RentalApiType;
use App\Repository\LocalRepository;
use App\Repository\RentalContractRepository;
use App\Repository\TenantRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LocalApiController extends BaseApiController
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
    public function __construct(LocalRepository $localRepository,NormalizerInterface $errorNormalizer,RentalContractRepository $contractRepository,UserRepository $userRepository,TenantRepository $tenantRepository)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
        $this->contratRepository = $contractRepository;
        $this->localRepository=$localRepository;
    }

    /**
     * Creates new Job resource
     *
     * @Rest\Post("/api/local/create")
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request){
        try {
            $form               = $this->createForm(LocalApiType::class, new Local());
            $data               = $this->getJsonDecodedFromRequest($request);
            $form->submit($data);
            if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);
                return View::create($response, Response::HTTP_BAD_REQUEST);
            }
            $this->saveServer($form->getNormData());
            return View::create($form->getNormData(), Response::HTTP_CREATED);
        }catch (\Exception $exception){
            return View::create($exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Creates new Job resource
     *
     * @Rest\Get("/api/local/list")
     * @param Request $request
     *
     * @return View
     */
    public function listRental(Request $request){
        $array = [];
        $idx = 0;
        try {
            $lists=$this->localRepository->findBy([]);
            foreach ($lists as $local){
                $temp=[
                    'id'=>$local->getId(),
                    'name'=>$local->getName(),
                    'status'=>$local->getStatus(),
                    'consistance'=>$local->getConsitance(),
                    'position'=>$local->getPosition(),
                    'adresse'=>$local->getAdresse(),
                    'price'=>$local->getPrice(),
                    'numberRoon'=>$local->getNumberRoon(),
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
     * Creates new Job resource
     *
     * @Rest\Get("/api/local/{id}")
     * @param Request $request
     *
     * @return View
     */
    public function getOne(Request $request){
        try {

            $local=$this->localRepository->find($request->get('id'));
                $temp=[
                    'id'=>$local->getId(),
                    'name'=>$local->getName(),
                    'status'=>$local->getStatus(),
                    'consistance'=>$local->getConsitance(),
                    'position'=>$local->getPosition(),
                    'adresse'=>$local->getAdresse(),
                    'price'=>$local->getPrice(),
                    'numberRoon'=>$local->getNumberRoon(),
                ];
                return View::create($temp, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    protected function saveServer(Local $local){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($local);
        $entityManager->flush();
    }
}