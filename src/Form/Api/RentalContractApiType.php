<?php

namespace App\Form\Api;

use App\Entity\Local;
use App\Entity\RentalContract;
use App\Form\DataTransformer\LocalIdTransformer;
use App\Form\DataTransformer\TenantIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalContractApiType extends AbstractType
{
    /**
     * @var LocalIdTransformer
     * @var TenantIdTransformer
     */
    protected $localIdTransformer;
    protected $tenantIdTransformer;

    /**
     * RentalContractApiType constructor.
     * @param $LocalIdTransformer
     * @param $tenantIdTransformer
     */
    public function __construct(LocalIdTransformer $localIdTransformer, TenantIdTransformer $tenantIdTransformer)
    {
        $this->localIdTransformer = $localIdTransformer;
        $this->tenantIdTransformer = $tenantIdTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',NumberType::class,[
            ])
            ->add('typeRental', TextType::class, [ ])
             ->add('amountGaranty')
            ->add('datedepotGaranty', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('amountPrevision')
            ->add('informationComplementaires')
            ->add('tenantId', NumberType::class, [
                'invalid_message' => 'Invalid tenantId received',
                'property_path'   => 'tenant',
            ])
            ->add('localId', NumberType::class, [
                'invalid_message' => 'Invalid tenantId received',
                'property_path'   => 'local',
                'mapped'=>false
            ])
        ;
        $builder->get('tenantId')
            ->addModelTransformer($this->tenantIdTransformer);
        $builder->get('localId')
            ->addModelTransformer($this->localIdTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RentalContract::class,
            'csrf_protection' => false,
        ]);
    }
}
