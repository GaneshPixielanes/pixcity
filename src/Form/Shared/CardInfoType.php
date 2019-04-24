<?php

namespace App\Form\Shared;

use App\Constant\CardInfoFieldType;
use App\Entity\CardInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'label.type',
                'choices'  => CardInfoFieldType::getList(),
            ))
            ->add('label', TextType::class, array('label' => 'label.url', 'attr' => ['placeholder'=>'label.label']))
            ->add('value', TextType::class, array('label' => 'label.value', 'attr' => ['placeholder'=>'label.value']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CardInfo::class,
            'type' => ''
        ));


    }
}