<?php

namespace App\Form;

use App\Entity\Local;
use App\Entity\RentalContract;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\LocalsInputType;
use App\Form\Type\TagsInputType;
use App\Repository\LocalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalContractType extends AbstractType
{
    private $localRepository;

    /**
     * RentalContractType constructor.
     * @param $localRepository
     */
    public function __construct(LocalRepository $localRepository)
    {
        $this->localRepository = $localRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',NumberType::class,[
                'translation_domain' => 'messages',
                'data'=>"0.0",
                'label' => 'dt.columns.amount',
                'attr'=>['class' => 'form-control','']
            ])
            ->add('typeRental', ChoiceType::class, [
                'placeholder' => 'veuillez choisir un type',
                'choices' => ['nuitee' => 'nuitee', ' mensual' => 'mensual',
                ],
                'translation_domain' => 'messages',
                'label' => 'dt.columns.type',
                'mapped'=>true,
                'attr' => ['class' => 'form-control select2', 'data-size' => 5, 'data-live-search' => true],
            ])
             ->add('amountGaranty',TextType::class,[
                 'label' => 'dt.columns.amountGaranty',
                 'attr'=>['class' => 'form-control','']
             ])
            ->add('datedepotGaranty', DateType::class, [
                'widget' => 'single_text',
                'label' => 'dt.columns.datedepotGaranty',
                'html5' => true,'attr'=>['class' => 'form-control','']
            ])
            ->add('amountPrevision',TextType::class,[
                'label' => 'dt.columns.amountPrevision',
                'attr'=>['class' => 'form-control','']
            ])
            ->add('informationComplementaires',TextareaType::class,[
                'attr'=>['class' => 'form-control','']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RentalContract::class,
        ]);
    }
}
