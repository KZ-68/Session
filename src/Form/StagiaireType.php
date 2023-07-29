<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('genre', TextType::class, [
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email'
                ]
            ])
            ->add('telephone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
