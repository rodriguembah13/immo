<?php


namespace App\Form\DataTransformer;


use App\Repository\TenantRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TenantIdTransformer implements DataTransformerInterface
{
    protected $tenantRepository;

    /**
     * TenantIdTransformer constructor.
     * @param $tenantRepository
     */
    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }


    public function transform($value)
    {
      return $value;
    }

    public function reverseTransform($tenantId)
    {
        if (empty($tenantId)) {
            throw new TransformationFailedException('Empty category received');
        }

        $tenantEntity = $this->tenantRepository->find($tenantId);

        if (empty($tenantEntity)) {
            throw new TransformationFailedException(sprintf(
                'A category with id "%s" does not exist!',
                $tenantId
            ));
        }

        return $tenantEntity;
    }
}