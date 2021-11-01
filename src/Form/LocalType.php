<?php

namespace App\Form;

use App\Entity\Local;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consitance', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une consitance',
                'label' => 'local.table.consitance',
                'translation_domain' => 'messages',
                'choices' => ['Appartement' => 'Appartement', 'Studio' => 'Studio',
                    'Chambre' => 'Chambre','Magasin' => 'Magasin','Boutique' => 'Boutique', ]
                ,'attr'=>['class' => 'form-control','']])
            ->add('numberRoon',IntegerType::class, [
                'required' => true,
                'label' => 'local.table.numberRoom',
                'translation_domain' => 'messages','attr'=>['class' => 'form-control','']
            ]) ->add('position', ChoiceType::class, [
                'placeholder' => 'veuillez choisir une position',
                'label' => 'local.table.position',
                'translation_domain' => 'messages',
                'choices' => ['Ras de chaussee' => '0', '1er etage' => '1',
                    '2e etage' => '2', ],'attr'=>['class' => 'form-control','']])
            ->add('adresse',TextType::class, [
                'required' => true,
                'label' => 'local.table.adresse',
                'translation_domain' => 'messages',
                'attr'=>['class' => 'form-control','']
            ])
            ->add('number',IntegerType::class, [
                'label' => 'local.table.number',
                'translation_domain' => 'messages',
                'required' => true,
                'mapped' => false,
                'attr'=>['class' => 'form-control','']
            ])
            ->add('status', ChoiceType::class, [
                'placeholder' => 'veuillez choisir un statut',
                'choices' => ['Disponible' => 'disponible', ' En travaux' => 'travaux',
                    'Occupé' => 'occupe', ],
                'attr'=>['class' => 'form-control','']])
            ->add('price',NumberType::class,[
                'label' => 'local.table.price',
                'translation_domain' => 'messages',
                'attr'=>['class' => 'form-control','']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Local::class,
        ]);
    }
}
