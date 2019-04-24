<?php

namespace App\Form\Admin;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $passwordRequired = true;
        if(!isset($options["type"]) || "edit" === $options["type"]){
            $passwordRequired = false;
        }

        $builder
            ->add('email', EmailType::class, array('label' => 'label.email'))
            ->add('firstname', TextType::class, array('label' => 'label.firstname'))
            ->add('lastname', TextType::class, array('label' => 'label.lastname'))
            ->add('roles', ChoiceType::class, array(
                'label' => 'label.role',
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices'  => array(
                    'label.administrator' => 'ROLE_ADMIN',
                    'label.moderator' => 'ROLE_MODERATOR'
                ),
            ))->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'label.password'),
                'second_options' => array('label' => 'label.repeatpassword'),
                'required' => $passwordRequired
            ));
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Admin::class,
            'type' => ''
        ));


    }
}