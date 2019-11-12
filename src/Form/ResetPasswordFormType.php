<?php

namespace App\Form;

use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, RepeatedType, PasswordType};


class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Email Id',
            'attr' => [
                'requred' => true,
                'placeholder' => 'Enter your registered email id here...',
                'class' => 'form-control',
            ],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'class' => 'form-control',
                ],
            ],
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
