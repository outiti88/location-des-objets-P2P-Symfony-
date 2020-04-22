<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($date)
    {
        if ($date === null) {
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchdate)
    {
        // frenchdate = 18/04/2020
        if ($frenchdate === null) {
            throw new TransformationFailedException();
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchdate);

        if ($date === false) {
            throw new TransformationFailedException("Le format de la date n'est pas le bon !");
        }

        return $date;
    }
}
