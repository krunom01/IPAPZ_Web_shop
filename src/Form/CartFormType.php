<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\CountryShipping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CartFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var \App\Entity\CountryShipping $country
         */
        $builder

            ->add('address')
            ->add(
                'country',
                EntityType::class,
                [
                    'class' => CountryShipping::class,
                    'choice_label' => function ($country) {
                        /**
                         * @var CountryShipping $country
                         */
                        return $country->getCountry();
                    },
                    'choice_value' => 'country',

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Cart::class,
            ]
        );
    }
}
