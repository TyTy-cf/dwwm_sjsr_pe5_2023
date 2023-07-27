<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Publisher;
use App\Repository\CountryRepository;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null, [
                'label' => 'form.game.name.label',
            ])
            ->add('price',null, [
                'label' => 'form.game.price.label',
            ])
            ->add('description',CKEditorType::class, [
                'label' => 'form.game.description.label',
            ])
            ->add('publishedAt',DateType::class, [
                'label' => 'form.game.published_at.label',
                'widget' => 'single_text',
            ])
            ->add('thumbnailCover',null, [
                'label' => 'form.game.thumbnail_cover.label',
            ])
            ->add('publisher',EntityType::class, [
                'label' => 'form.game.publisher.label',
                'required' => false,
                'class' => Publisher::class,
                'choice_label' => 'name',
                'query_builder' => function (PublisherRepository $pr) {
                    return $pr->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                }
            ])
            ->add('categories',CollectionType::class, [
                'label' => 'form.game.categories.label',
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.name', 'ASC');
                    }
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-list-selector' => 'categories',
                ]
            ])
            ->add('addCategory',ButtonType::class, [
                'label' => 'Ajouter une catégorie',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'data-btn-selector' => 'categories',
                ]
            ])
            ->add('countries',CollectionType::class, [
                'label' => 'form.game.countries.label',
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'label' => false,
                    'class' => Country::class,
                    'choice_label' => 'nationality',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.nationality', 'ASC');
                    }
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => [
                    'data-list-selector' => 'country',
                ]
            ])
            ->add('addCountry',ButtonType::class, [
                'label' => 'Ajouter un pays',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'data-btn-selector' => 'country',
                ]
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $form = $event->getForm();

            $field = $form->get('thumbnailCover');
            $fieldLink = $form->get('thumbnailCoverLink');
            if ($field->getData() === null && $fieldLink->getData() === null) {
                $form->addError(new FormError('MET AU MOINS UNE IMAGE'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
