<?php

namespace App\Form;

use App\Entity\Session;
use App\Form\ProgrammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProgrammesSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add("programmes", CollectionType::class, [
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
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
