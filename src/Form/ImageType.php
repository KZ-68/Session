<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filename', FileType::class, [
                'label' => 'Image',
                // unmapped veut dire que le champ n'est associé à aucun attribut de l'entité
                'mapped' => false,
                // Mettre en false pour éviter de devoir remettre le fichier à chaque modification
                'required' => false,
                // Les champs unmapped ne peuvent pas définir leur validation en utilisant les annotations
                // dans l'entité associée, donc il faut utiliser les classes constraints de PHP
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                            'image/x-icon',
                            'image/tiff',
                            'image/bmp'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image format',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class
        ]);
    }
}
