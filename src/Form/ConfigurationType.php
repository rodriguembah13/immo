<?php

namespace App\Form;

use App\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.designation','attr'=>['class' => 'form-control','']
            ])
            ->add('phone',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.phone','attr'=>['class' => 'form-control','']
            ])
            ->add('siteweb',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.website','attr'=>['class' => 'form-control','']
            ])
            ->add('adresse',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.email',
                'attr'=>['class' => 'form-control','']
            ])
            ->add('bp',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.bp','attr'=>['class' => 'form-control','']
            ])
            ->add('logo',FileType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.logo',
                'required'=>false,
                'attr'=>['class' => 'form-control','']
            ])
            ->add('directeur',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'dt.columns.director','attr'=>['class' => 'form-control','']
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'dt.columns.mode',
                'translation_domain' => 'messages',
                'choices' => ['Simple' => 'Simple', 'Advanced' => 'Advanced'],
                'attr'=>['class' => 'form-control','']])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Configuration::class,
        ]);
    }
}
