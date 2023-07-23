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
            'entry_type' => ProgrammeType::class,
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
