<?php

namespace App\Form;

use App\Entity\CommentClient;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentClientType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfiguration(
                "Note sur 5",
                "Veuillez indiquer votre note de 0 à 5",
                [
                    'attr' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 1
                    ]
                ]
            ))
            ->add('positiveComment', TextareaType::class, $this->getConfiguration(
                "Votre avis / témoignage (positif)",
                "N'hésitez pas à être trés précis, cela aidera nos futurs clients !",
                [
                    'required' => false
                ]
            ))
            ->add('negativeComment', TextareaType::class, $this->getConfiguration(
                "Votre avis / témoignage (negatif)",
                "N'hésitez pas à être trés précis, cela aidera nos futurs clients !",
                [
                    'required' => false
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentClient::class,
        ]);
    }
}
