<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('username', TextType::class, [
                'label' => 'label.username',
                'disabled' => false,
                'attr'=>['class' => 'form-control','']
            ])
            ->add('fullName', TextType::class, [
                'label' => 'dt.columns.name',
                'attr'=>['class' => 'form-control','']
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email','attr'=>['class' => 'form-control','']
            ])
            ->add('password',PasswordType::class,[
                'attr'=>['class' => 'form-control','']
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => ['ROLE_USER'=>'ROLE_USER','ROLE_STOCK'=>'ROLE_STOCK','ROLE_ADMIN'=>'ROLE_ADMIN'],
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}