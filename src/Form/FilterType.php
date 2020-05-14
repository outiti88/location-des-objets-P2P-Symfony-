<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Filter;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class FilterType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'city',
                EntityType::class,
                $this->getConfiguration(
                    "Ville",
                    "Selectionnez la ville",
                    [
                        'class' => City::class,
                        'choice_label' => 'name'
                    ]
                )
            )

            ->add(
                'startDate',
                TextType::class,
                $this->getConfiguration("Date pour commencer", "dd/mm/yyyy", [
                    'required' => false
                ])
            )

            ->add(
                'endDate',
                TextType::class,
                $this->getConfiguration("Date pour finir", "dd/mm/yyyy", [
                    'required' => false
                ])
            )

            ->add(
                'startPrice',
                MoneyType::class,
                $this->getConfiguration("Prix Min", "Indiquez le prix Min (DH)", [
                    'required' => false
                ])
            )

            ->add(
                'endPrice',
                MoneyType::class,
                $this->getConfiguration("Prix Max", "Indiquez le prix Max (DH)", [
                    'required' => false
                ])
            )

            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'Catégorie',
                    'class' => Category::class,
                    'choice_label' => 'title',
                ]
            )

            ->add(
                'subCategory',
                EntityType::class,
                [
                    'label' => 'Sous-catégorie',
                    'class' => SubCategory::class,
                    'choice_label' => 'title',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
