<?php

namespace App\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageFileType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File(
                    mimeTypes: ['image/png', 'image/jpeg'],
                    mimeTypesMessage: 'Déposer seulement un .jpg ou .png'
                )
            ],
        ]);
    }

    public function getParent(): string
    {
        return FileType::class;
    }

}
