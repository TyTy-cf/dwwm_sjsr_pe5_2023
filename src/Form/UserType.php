<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCreation = $options['is_creation'];

        $builder
            ->add('password',  PasswordType::class, [
                'label' => 'form.user.password.label',
                'required' => $isCreation,
            ])
            ->add('nickname', null, [
                'label' => 'form.user.nickname.label',
            ]);
        
        if ($isCreation) {
            $builder->add('name', null, [
                'label' => 'form.user.name.label',
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.user.email.label',
            ]);
        }

        if (false === $isCreation) {
            $builder->add('country', EntityType::class, [
                'label' => 'form.user.country.label',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function (CountryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('profileImage', FileType::class,[
                'label' => 'form.user.profile_image.label',
                'required' => false,
                'mapped' => false, // => Dit à Symfony : t'inquiètes, je le gère moi-même
                'constraints' => [
                    new File(
                        maxSize: '3M',
                        mimeTypes: ['image/png', 'image/jpeg'],
                        maxSizeMessage: 'Ton fichier est trop lourd !',
                        mimeTypesMessage: 'Déposer seulement un .jpg ou .png'
                    )
                ]
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_creation' => true, // Si vous souhaitez ajouter une option au form
        ]);
    }
}
