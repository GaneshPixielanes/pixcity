<?php

namespace App\Form\Admin;

use App\Entity\UserRegistrationCheck;
use App\Form\Type\SwitchType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationCheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('manualRegistration',ChoiceType::class,[
            'label' => 'Type inscription',
            'required' => true,
            'choices' => ['Naturel' => 0, 'Demarchage' => 1]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationCheck::class,
            'type' => ''
        ]);
    }
}
