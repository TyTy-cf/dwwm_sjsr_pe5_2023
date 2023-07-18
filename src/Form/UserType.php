<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\User;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isCreation = $options['is_creation'];

        $builder
            ->add('password',  PasswordType::class, [
                'label' => 'form.user.password.label',
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

        if (!$isCreation) {
            $builder->add('country', EntityType::class, [
                'label' => 'form.user.country.label',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function (CountryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
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
