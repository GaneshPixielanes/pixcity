<?php

namespace App\Form\Admin;

use App\Entity\Region;
use App\Form\Type\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name'))
            ->add('slug', TextType::class, array('label' => 'label.slug', 'required' => false))
            ->add('hidden', SwitchType::class, array('label' => 'label.hidden', 'required' => false))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Region::class,
            'type' => ''
        ));

    }
}