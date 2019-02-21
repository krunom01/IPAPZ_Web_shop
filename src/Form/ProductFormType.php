<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choice_label' => 'name'
            ])
            ->add('sku', IntegerType::class, [
                'label' => 'sku',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('productnumber', IntegerType::class, [
                'label' => 'productnumber',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'price',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('image', TextareaType::class, [
                'label' => 'image',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
