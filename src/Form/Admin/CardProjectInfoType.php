<?php

namespace App\Form\Admin;

use App\Constant\CardInfoFieldType;
use App\Entity\CardProjectInfo;
use App\Form\Type\SwitchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardProjectInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'label.type',
                'choices'  => CardInfoFieldType::getList(),
            ))
            ->add('label', TextType::class, array('label' => 'label.url', 'attr' => ['placeholder'=>'label.label']))
            ->add('mandatory', SwitchType::class, array('label' => 'label.mandatory', 'required' => false, 'attr' => []))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CardProjectInfo::class,
            'type' => ''
        ));


    }
}