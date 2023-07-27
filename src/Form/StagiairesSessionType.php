<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiairesSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("stagiaires", EntityType::class, [
                'class' => Stagiaire::class,
                // Permet d'afficher les prenom et nom dans les choix
                'choice_label' => function ($allChoices, $currentChoiceKey)
                {
                    return $allChoices->getPrenom() . " " . $allChoices->getNom() . " " ;
                },
                // Si mis en true, des boutons radios ou des checkbox sont affichés
                // En fonction de la valeur de 'multiple'
                'expanded'  => true,
                // Autorise plusieurs choix
                'multiple'  => true,
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
