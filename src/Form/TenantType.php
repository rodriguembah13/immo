<?php

namespace App\Form;

use App\Entity\Tenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.name','attr'=>['class' => 'form-control','']
            ])
            ->add('situation',ChoiceType::class,[
                'placeholder' => 'veuillez choisir une situation',
                'choices' => ['marie' => 'Marié(e)', ' celibataire' => 'Celibataire',
                ],'attr'=>['class' => 'form-control','']
            ])
            ->add('numberChild',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.number_child','attr'=>['class' => 'form-control','']
            ])
            ->add('profession',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.profession','attr'=>['class' => 'form-control','']
            ])
            ->add('cni',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.cni','attr'=>['class' => 'form-control','']
            ])
            ->add('nationality',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.nationality','attr'=>['class' => 'form-control','']
            ])
            ->add('phone',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.phone','attr'=>['class' => 'form-control','']
            ])
            ->add('email',TextType::class,[
                'translation_domain' => 'messages',
                'label' => 'tenant.table.email','attr'=>['class' => 'form-control','']
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'photo','attr'=>['class' => 'custom-file-input','']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tenant::class,
        ]);
    }
}
