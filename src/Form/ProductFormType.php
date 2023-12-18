<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('qty')
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, array(
                'required' => false,
                'mapped' => false,

            ))
            ->add('category', EntityType::class, array('class' => 'App\Entity\Category', 'choice_label' => "title"))
            ->add('brand', EntityType::class, array('class' => 'App\Entity\Brand', 'choice_label' => "name"));
        // ->add('category')
        // ->add('brand');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}