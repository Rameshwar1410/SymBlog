<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextareaType};

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('comment', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',
                'rows' => "3",
            ],
        ])
        ->add('Update', SubmitType::class, [
            'attr' => [
                'placeholder' => 'First Name',
                'class' => 'btn btn-success',
                'style' => 'margin-top:3%;',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
