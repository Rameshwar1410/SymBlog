<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, FileType, ChoiceType};


class UsersFormType extends AbstractType
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
            ->add('image', FileType::class, [
                'attr' => [
                    'mapped' => false,
                    'label' => 'Please uploade a Image',
                    'class' => 'form-control',
                    
                ],
                'data_class' => null,
                'empty_data' => null,
                'required' => false                
            ])
            ->add('roles', ChoiceType::class, array(
                'attr' => array(
                    'class' => 'form-control selectpicker',
                    'required' => false,
                ),
                'multiple' => true,
                'expanded' => false, // render check-boxes
                'choices' => [
                    'admin' => 'ROLE_ADMIN',
                    'user' => 'ROLE_USER',
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
