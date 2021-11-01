<?php


namespace App\Form\Api;


use App\Entity\Local;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalApiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consitance', TextType::class, [])
            ->add('numberRoon',IntegerType::class, [
                'required' => true,
            ]) ->add('position', TextType::class, [])
            ->add('adresse',TextType::class, [
                'required' => true,
            ])
            ->add('number',IntegerType::class, [
                'mapped'=>false
            ])
            ->add('status', TextType::class, [])
            ->add('price',NumberType::class,[
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Local::class,
            'csrf_protection' => false,
        ]);
    }
}
