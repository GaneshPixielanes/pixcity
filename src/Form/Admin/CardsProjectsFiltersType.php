<?php

namespace App\Form\Admin;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use App\Entity\Department;
use App\Entity\Region;
use App\Form\Type\SwitchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardsProjectsFiltersType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('region', EntityType::class, array(
                'class' => Region::class,
                'label' => 'label.region',
                'required' => false,
                'placeholder' => 'label.select.region',
                'choice_label' => 'name',
            ))
            ->add('department', EntityType::class, array(
                'class' => Department::class,
                'label' => 'label.department',
                'required' => false,
                'placeholder' => 'label.select.department',
                'choice_label' => 'name',
            ))
            ->add('status', ChoiceType::class, array(
                'label' => 'label.status',
                'choices'  => CardProjectStatus::getList(),
                'placeholder' => 'label.choose',
                'required' => false,
            ))
            ->add('late', SwitchType::class, array('label' => 'label.late', 'required' => false))
            ->add('isInterview', ChoiceType::class,[
                'label' => 'Card Type',
                'choices' => ['Card' => '0', 'Interview' => 1],
                'placeholder' => 'label.choose',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'type' => ''
        ));


    }
}