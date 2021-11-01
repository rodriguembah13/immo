<?php


namespace App\Form\DataTransformer;


use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserIdTransformer implements DataTransformerInterface
{
    protected $userRepository;

    /**
     * UserIdTransformer constructor.
     * @param $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($entityId)
    {
        if (empty($entityId)) {
            throw new TransformationFailedException('Empty user received');
        }

        $entity = $this->userRepository->find($entityId);

        if (empty($entity)) {
            throw new TransformationFailedException(sprintf(
                'A user with id "%s" does not exist!',
                $entityId
            ));
        }

        return $entity;
    }
}