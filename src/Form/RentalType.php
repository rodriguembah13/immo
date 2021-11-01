<?php

namespace App\Form;

use App\Entity\Rental;
use App\Entity\Tenant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tenant', EntityType::class, [
                'class' => Tenant::class,
                'multiple' => false,
                'placeholder' => 'rental.table.tenant',
                'translation_domain' => 'messages',
                'label' => 'rental.table.tenant',
                'required' => true,
                'attr' => ['class' => 'form-control select2', 'data-size' => 5,'data-type'=>'', 'data-live-search' => true],
                'choice_attr'=>function($obj){
              return  ['data-flag'=>$obj->getId()];
                }
            ])
            ->add('typeRental', ChoiceType::class, [
                'placeholder' => 'veuillez choisir un statut',
                'choices' => ['Nuitée' => 'nuitee', ' Mentionatly' => 'mentionatly',
                ],
                'mapped'=>true,
                'attr' => ['class' => 'form-control select2', 'data-size' => 5, 'data-live-search' => true],
                'translation_domain' => 'messages',
                'label' => 'rental.table.type',
            ])
            ->add('year', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une annee',
                'choices' => ['2020' => '2000', ' 2021' => '2021', ' 2022' => '2022',
                ],
                'translation_domain' => 'messages',
                'label' => 'rental.table.year',
                'mapped'=>true,
                'attr' => ['class' => 'select2', 'data-size' => 5, 'data-live-search' => true],
            ])
            ->add('amount',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'rental.table.amount',
                'attr'=>['class' => 'form-control','']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
