<?php


namespace App\Form\Api;


use App\Entity\Rental;
use App\Entity\Tenant;
use App\Form\DataTransformer\TenantIdTransformer;
use App\Form\DataTransformer\UserIdTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalApiType extends AbstractType
{
    /**
     * @var UserIdTransformer
     */
    protected $userIdTransformer;
    protected $tenantIdTransformer;

    /**
     * RentalApiType constructor.
     * @param UserIdTransformer $userIdTransformer
     * @param TenantIdTransformer $tenantIdTransformer
     */
    public function __construct(UserIdTransformer $userIdTransformer, TenantIdTransformer $tenantIdTransformer)
    {
        $this->userIdTransformer = $userIdTransformer;
        $this->tenantIdTransformer = $tenantIdTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tenantId', NumberType::class, [
                'invalid_message' => 'Invalid tenantId received',
                'property_path'   => 'tenant',
            ])
            ->add('typeRental', TextType::class, [
                'label' => 'rental.table.type',
            ])
            ->add('year', TextType::class, [

            ])
            ->add('month', TextType::class, [

            ])
            ->add('amount',NumberType::class,[
                'required'=>true,
                'invalid_message' => 'Invalid tenantId received',
            ])
            ->add('createdById', NumberType::class, [
                'invalid_message' => 'Invalid user id received',
                 'property_path'   => 'createdBy',
            ]);

        $builder->get('tenantId')
            ->addModelTransformer($this->tenantIdTransformer);
        $builder->get('createdById')
            ->addModelTransformer($this->userIdTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
            'csrf_protection' => false,
        ]);
    }
}
