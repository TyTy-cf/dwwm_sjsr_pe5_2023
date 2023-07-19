<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null, [
                'label' => 'form.publisher.name.label',
            ])
            ->add('website',null, [
                'label' => 'form.publisher.website.label',
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'form.publisher.created_at.label',
            ])
            ->add('country', EntityType::class, [
                'label' => 'form.user.country.label',
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => function (CountryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
        ]);
    }
}
