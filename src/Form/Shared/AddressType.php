<?php

namespace App\Form\Shared;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, array('label' => 'label.address', 'required' => true, 'attr' => ['placeholder' => 'label.address', 'class' => 'gm-address form-control']))
            ->add('zipcode', TextType::class, array('label' => 'label.zipcode', 'required' => true, 'attr' => ['placeholder' => 'label.zipcode', 'class' => 'gm-zipcode form-control']))
            ->add('city', TextType::class, array('label' => 'label.city', 'required' => true, 'attr' => ['placeholder' => 'label.city', 'class' => 'gm-city form-control']))
            ->add('country', CountryType::class, array('label' => 'label.country', 'required' => true, "preferred_choices" => array('FR'), 'attr' => ['placeholder' => 'label.country', 'class' => 'gm-country form-control']))
            ->add('latitude', TextType::class, array('label' => 'label.latitude', 'required' => false, 'attr' => ['class' => 'gm-latitude form-control']))
            ->add('longitude', TextType::class, array('label' => 'label.longitude', 'required' => false, 'attr' => ['class' => 'gm-longitude form-control']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Address::class,
            'type' => ''
        ));


    }
}