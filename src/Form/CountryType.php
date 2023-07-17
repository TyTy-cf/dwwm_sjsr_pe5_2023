<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'form.country.name.label',
                'attr' => [
                    'maxlength' => 24,
                    'placeholder' => 'form.country.name.placeholder',
                ]
            ])
            ->add('nationality', null, [
                'label' => 'form.country.nationality.label',
                'attr' => [
                    'maxlength' => 24,
                    'placeholder' => 'form.country.nationality.placeholder',
                ]
            ])
            ->add('code', null, [
                'label' => 'form.country.code.label',
                'attr' => [
                    'maxlength' => 24,
                    'placeholder' => 'form.country.code.placeholder',
                ]
            ])
//            ->add('submit', SubmitType::class, [
//
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
