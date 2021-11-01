<?php

namespace App\Form;

use App\Entity\Depense;
use App\Entity\Local;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr'=>['class' => 'form-control','']
            ])
            ->add('depenseType', EntityType::class, [
                'class' => \App\Entity\DepenseType::class,
                'multiple' => false,
                'placeholder' => 'depense.table.typedepense',
                'translation_domain' => 'messages',
                'label' => 'depense.table.typedepense',
                'required' => true,
                'attr' => ['class' => 'select2 form-control', 'data-size' => 5,'data-type'=>'', 'data-live-search' => true],

            ])
            ->add('local', EntityType::class, [
                'class' => Local::class,
                'multiple' => false,
                'placeholder' => 'depense.table.local',
                'translation_domain' => 'messages',
                'label' => 'depense.table.local',
                'required' => true,
                'attr' => ['class' => 'select2 form-control', 'data-size' => 5,'data-type'=>'', 'data-live-search' => true],

            ])
            ->add('amount',TextType::class,[
                'attr'=>['class' => 'form-control','']
            ])
            ->add('dateAchat', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr'=>['class' => 'form-control','']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depense::class,
        ]);
    }
}
