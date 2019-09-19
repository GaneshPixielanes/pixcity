<?php

namespace App\Form\Admin;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('profilePhoto', FileType::class, [
                'label' => 'ProfilePhoto',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,

            ])
            ->add('linkedinProfile', TextType::class, array('label' => 'Linkedin Profile'))
            ->add('roles', ChoiceType::class, array(
                'label' => 'label.role',
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices'  => array(
                    'label.administrator' => 'ROLE_ADMIN',
                    'label.moderator' => 'ROLE_MODERATOR',
                    'label.b2c' => 'ROLE_B2C'
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