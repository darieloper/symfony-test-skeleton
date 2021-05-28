<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\FileValidator;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'attr' => [
                    'class' => 'form-control col-lg-8 col-md-12 col-sm-12',
                    'accept' => 'image/x-png, image/gif, image/jpeg',
                ],
            ])
            ->add('title', null, ['attr' => ['class' => 'form-control col-lg-8 col-md-12 col-sm-12 mb-2']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
