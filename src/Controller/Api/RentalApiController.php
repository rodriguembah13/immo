<?php


namespace App\Controller\Api;


use App\Entity\Rental;
use App\Form\Api\RentalApiType;
use App\Repository\RentalContractRepository;
use App\Repository\RentalRepository;
use App\Repository\TenantRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RentalApiController extends BaseApiController
{
    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;
    protected $rentalRepository;
    protected $tenantRepository;
    protected $userRepository;
    protected $contratRepository;

    /**
     * RentalApiController constructor.
     * @param NormalizerInterface $errorNormalizer
     */
    public function __construct(RentalContractRepository $contractRepository,UserRepository $userRepository,TenantRepository $tenantRepository,RentalRepository $rentalRepository,NormalizerInterface $errorNormalizer)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->rentalRepository=$rentalRepository;
        $this->userRepository=$userRepository;
        $this->tenantRepository=$tenantRepository;
        $this->contratRepository=$contractRepository;
    }
    /**
     * Creates new Job resource
     *
     * @Rest\Post("/api/rental/create")
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request){
        try {
            $form               = $this->createForm(RentalApiType::class, new Rental());
            $data               = $this->getJsonDecodedFromRequest($request);
            $form->submit($data);
            if (!$form->isValid()) {
                $response = $this->getErrors($this->errorNormalizer, $form, Response::HTTP_BAD_REQUEST);
                return View::create($response, Response::HTTP_BAD_REQUEST);
            }
            $this->registerRental($data);
            return View::create($data, Response::HTTP_CREATED);
        }catch (\Exception $exception){
            return View::create($exception->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Creates new Job resource
     *
     * @Rest\Get("/api/rental/list")
     * @param Request $request
     *
     * @return View
     */
    public function listRental(Request $request){
        $array = [];
        $idx = 0;
        try {
            $lists=$this->rentalRepository->findBy(['active'=>true]);
        foreach ($lists as $rental){
            $temp=[
                'id'=>$rental->getId(),
                'tenantId'=>$rental->getTenant()->getId(),
                'createdById'=>$rental->getCreatedBy()->getId(),
                'amount'=>$rental->getAmount(),
                'amountDue'=>$rental->getAmountDue(),
                'month'=>$rental->getMonth(),
                'year'=>$rental->getYear(),
                'typeRental'=>$rental->getTypeRental(),
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
     * @Rest\Get("/api/rental/{id}")
     * @param Request $request
     *
     * @return View
     */
    public function getOne(Request $request){
        $array = [];
        $idx = 0;
        try {
            $rental=$this->rentalRepository->find($request->get('id'));

                $temp=[
                    'id'=>$rental->getId(),
                    'tenantId'=>$rental->getTenant()->getId(),
                    'createdById'=>$rental->getCreatedBy()->getId(),
                    'amount'=>$rental->getAmount(),
                    'amountDue'=>$rental->getAmountDue(),
                    'month'=>$rental->getMonth(),
                    'year'=>$rental->getYear(),
                    'typeRental'=>$rental->getTypeRental(),
                ];

            return View::create($temp, Response::HTTP_CREATED);
        } catch (\Exception $ex) {
            //Log exception
            return View::create($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function registerRental($data){
        $entityManager = $this->getDoctrine()->getManager();
        $amount=$data['amount'];
        $month=$data['month'];
        $year=$data['year'];
        $tenant=$this->tenantRepository->find($data['tenantId']);
        $createdBy=$this->userRepository->find($data['createdById']);
        $rendal_server = $this->rentalRepository->findBy(['month' => $month, 'year' => $year, 'tenant' => $tenant,'active'=>true]);
        if ($rendal_server == null) {
            $contrat = $this->contratRepository->findOneBySomeField($tenant);
            $rendal = new Rental();
            $rendal->setTenant($tenant);
            $rendal->setAmount($contrat->getAmount());
            $rendal->setAmountDue($contrat->getAmount());
            $rendal->setTypeRental($contrat->getTypeRental());
            $rendal->setCreatedBy($createdBy);
            $rendal->setMonth($month);
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
           // $this->addFlash('success', 'Rendal for ' . $tenant->getName() . 'is created with success');
        }
    }
}