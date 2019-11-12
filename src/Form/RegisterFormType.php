<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, 
NumberType, PasswordType, FileType, RepeatedType, SubmitType};

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('userName', TextType::class, [
                'attr' => [
                    'placeholder' => 'User Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email Id',
                    'class' => 'form-control'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('image', FileType::class, [
                'attr' => [
                    'mapped' => false,
                    'label' => 'Please uploade a Image',
                    'class' => 'form-control',
                    'required' => 'false'
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => [
                    'style' => 'margin-top:5%',
                    'class' => 'from-control btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
