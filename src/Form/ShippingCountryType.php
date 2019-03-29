<?php

namespace App\Form;

use App\Entity\CountryShipping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingCountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'country'
            )
            ->add(
                'countryCode'
            )
            ->add(
                'shippingPrice'
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => CountryShipping::class,
            ]
        );
    }
}
