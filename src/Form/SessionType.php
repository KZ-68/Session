<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Form\ProgrammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control' 
                ]
            ])
            ->add('nbMax', IntegerType::class, [
                'empty_data' => '0',
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'intituleFormation'
            ])
            ->add('programmes', CollectionType::class, [
                // La collection attend l'élément qu'elle entrera dans le form
                // Ce n'est pas obligatoire que ce soit un autre form
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                /* On va autoriser l'ajout de nouveaux éléments dans l'entité session qui serons persisté grâce à cascade_persist 
                sur l'élément programme */
                // Ca va activer un data prototype qui sera un attribut html qu'on pourra manipuler en javascript
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // Obligatoire car Session n'a pas de setProgramme, mais c'est Programme qui a setSession
                // C'est Programme qui est propriété de la relation
                // Pour éviter un mapping false on est obligé de rajouter un by_reference
                'entry_options' => [
                    'attr' => ['class' => 'form-options'],
                ],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success' 
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
