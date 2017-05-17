<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\Specs\ValueType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array())
            ->add('description', TextareaType::class, array())
            ->add('price', NumberType::class, array())
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle\Entity\ProductCategory'
            ))
            ->add('specifications', CollectionType::class, array(
                'entry_type' => ValueType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class
        ));
    }
}