<?php

namespace App\Form\Api;

use App\Entity\Tenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenantApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
            ])
            ->add('situation')
            ->add('numberChild',TextType::class,[
            ])
            ->add('profession',TextType::class,[
            ])
            ->add('cni',TextType::class,[
            ])
            ->add('nationality',TextType::class,[
            ])
            ->add('phone',TextType::class,[
            ])
            ->add('email',TextType::class,[
            ])
          /*  ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'photo',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tenant::class,
            'csrf_protection' => false,
        ]);
    }
}
