<?php

namespace App\Form;

use App\Entity\CountryShipping;
use App\Entity\Order;
use App\Entity\PaymentType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var \App\Entity\PaymentType $paymentType
         * @var \App\Entity\CountryShipping $country
         */
        $builder
            ->add(
                'type',
                EntityType::class,
                [
                    'class' => PaymentType::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('type')
                            ->WHERE('type.visibility = 1');
                    },
                    'choice_label' => function ($paymentType) {
                        /**
                         * @var PaymentType $paymentType
                         */
                        return $paymentType->getType();
                    },
                    'choice_value' => 'type',

                ]
            )
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
            )
            ->add('address');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => Order::class,
            ]
        );
    }

}
