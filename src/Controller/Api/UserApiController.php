<?php


namespace App\Controller\Api;


use App\Repository\LocalRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserApiController extends BaseApiController
{
    /**
     * @var NormalizerInterface
     */
    protected $errorNormalizer;
    protected $userRepository;
    protected $localRepository;

    /**
     * UserApiController constructor.
     * @param NormalizerInterface $errorNormalizer
     * @param $userRepository
     */
    public function __construct(LocalRepository $localRepository,NormalizerInterface $errorNormalizer, UserRepository $userRepository)
    {
        $this->errorNormalizer = $errorNormalizer;
        $this->userRepository = $userRepository;
        $this->localRepository=$localRepository;
    }

    /**
     * Creates new Job resource
     *
     * @Rest\Get("/api/users/list")
     * @param Request $request
     *
     * @return View
     */
    public function listRental(Request $request){
        $array = [];
        $idx = 0;
        $lists=$this->userRepository->findAll();
        foreach ($lists as $user){
            $temp=[
                'id'=>$user->getId(),
            ];
            $array[$idx++] = $temp;
        }
        return View::create($array, Response::HTTP_CREATED);
    }
}