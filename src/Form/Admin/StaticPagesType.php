<?php

namespace App\Form\Admin;

use App\Entity\StaticPages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StaticPagesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name'))
            ->add('title', TextType::class, array('label' => 'label.metatitle'))
            ->add('url', TextType::class, array('label' => 'label.slug', 'required' => true, 'disabled'=>true))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StaticPages::class,
            'type' => ''
        ));

    }
}