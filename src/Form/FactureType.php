<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Tenant;
use App\Repository\RentalRepository;
use App\Repository\TenantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    private $rentalRepository;
    private $tenantRepository;

    /**
     * FactureType constructor.
     * @param $rentalRepository
     * @param $tenantRepository
     */
    public function __construct(RentalRepository $rentalRepository, TenantRepository $tenantRepository)
    {
        $this->rentalRepository = $rentalRepository;
        $this->tenantRepository = $tenantRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tenant', EntityType::class, [
                'class' => Tenant::class,
                'multiple' => false,
                'placeholder' => 'bill.table.tenant',
                'translation_domain' => 'messages',
                'label' => 'dt.columns.tenant',
                'required' => true,
                'attr' => ['class' => 'select2 form-control', 'data-size' => 5,'data-type'=>'', 'data-live-search' => true],
                'choice_attr'=>function($obj){
                    return  ['data-flag'=>$obj->getId()];
                }
            ]) ->add('amountDue',TextType::class,[
                'label' => 'bill.table.amount_due',
                'translation_domain' => 'messages',
                'mapped'=>false,
                'disabled'=>true,'attr'=>['class' => 'form-control','']
            ])
            //->add('total')
            ->add('amount',TextType::class,[
                'label' => 'bill.table.amount',
                'translation_domain' => 'messages',
                'attr'=>['class' => 'form-control','']
            ])

            //->add('amountDue')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
