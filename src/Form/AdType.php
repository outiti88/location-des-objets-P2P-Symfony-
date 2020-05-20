<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('Titre', 'Titre de l\'annonce')
            )

            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce")
            )

            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration("Description détaillée", "Tapez une description qui donne vraiment envie de louer votre article")
            )

            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration("URL de l'image", "Donnez l'adresse d'une image qui donne vraiment envie")
            )

            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration("Prix par jour", "Indiquez le prix que vous voulez pour un jour")
            )

            ->add(
                'dateDebut',
                TextType::class,
                $this->getConfiguration("Date de disponibilité", "La date à laquelle votre bien sera disponible")
            )

            ->add(
                'dateFin',
                TextType::class,
                $this->getConfiguration("Date de fin de disponibilité", "La date à laquelle votre bien ne sera plus disponible")
            )

            ->add(
                'cities',
                EntityType::class,
                $this->getConfiguration(
                    "Ville",
                    "Sélectionnez votre ville",
                    [
                        'class' => 'App\Entity\City',
                        'choice_label' => 'name',
                        'multiple' => true
                    ]
                )
            )

            ->add(
                'category',
                EntityType::class,
                $this->getConfiguration("Catégorie", "Quel objet ?", [
                    'class' => 'App\Entity\Category',
                    'choice_label' => 'title',
                    'mapped' => false
                ])
            )

            ->add(
                'subCategory',
                EntityType::class,
                $this->getConfiguration("sous-catégorie", "Préciser", [
                    'class' => 'App\Entity\SubCategory',
                    'choice_label' => 'title'
                ])
            )

            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )

            ->add('premiumValue', CheckboxType::class, [
                'label'    => 'L\'option premium vous pemet d\'afficher votre annonce dans les premières pages',
                'required' => false,
            ])

            ->add('premiumDuration', ChoiceType::class, array(
                'choices'  => array(
                    '7 jours' => 7,
                    '15 jours' => 15,
                    '1 mois' => 30,
                )
            ));

        $builder->get('dateDebut')->addModelTransformer($this->transformer);
        $builder->get('dateFin')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
