<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('price')
            ->add('description')
            ->add('stock')
            ->add('tags', EntityType::class, [
                'class'        => Tag::class,
                'choice_label' => 'name',
                'multiple'     => true,
            ])
            ->add('removed', HiddenType::class, ['label' => false, 'mapped' => null])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'mapped' => false,
                'allow_add' => true,
                'prototype' => true,
                'label' => false,
                'entry_options' => ['label' => false],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'cascade_validation' => true,
        ]);
    }
}
