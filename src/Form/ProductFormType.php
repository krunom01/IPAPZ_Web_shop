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
use Symfony\Component\Form\Extension\Core\Type\FileType;


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
            ->add('image', FileType::class, [
                'label' => 'Insert Your Image here (jpg, jpeg): '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
