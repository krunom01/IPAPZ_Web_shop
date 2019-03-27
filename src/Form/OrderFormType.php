<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\PaymentType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\PaymentTypeRepository;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @var \App\Entity\PaymentType $paymentType
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
            ->add('state')
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
