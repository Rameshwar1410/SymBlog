<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{TextType, TextareaType};

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'First Name',
                    'class' => 'form-control'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'First Name',
                    'class' => 'form-control content'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
