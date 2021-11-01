<?php


namespace App\Form\DataTransformer;


use App\Repository\LocalRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LocalIdTransformer implements DataTransformerInterface
{
    protected $localRepository;

    /**
     * LocalIdTransformer constructor.
     * @param $localRepository
     */
    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;
    }

    public function transform($value)
    {
        return $value;
    }

    public function reverseTransform($entityId)
    {
        if (empty($entityId)) {
            throw new TransformationFailedException('Empty category received');
        }

        $entity = $this->localRepository->find($entityId);

        if (empty($entity)) {
            throw new TransformationFailedException(sprintf(
                'A category with id "%s" does not exist!',
                $entityId
            ));
        }

        return $entity;
    }
}