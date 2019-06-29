<?php

namespace App\Form;

use App\Entity\Client;
use App\Form\B2B\ClientInfoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $passwordRequired = true;
        if(!isset($options["type"]) || "edit" === $options["type"]){
            $passwordRequired = false;
        }
        $builder
            ->add('email')
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'label.password'),
                'second_options' => array('label' => 'label.repeatpassword'),
                'required' => $passwordRequired
            ))
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('roles', ChoiceType::class,array(
                'label' => 'label.role',
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices'  => array(
                    'label.user' => 'ROLE_USER',
                    'label.moderator' => 'ROLE_MODERATOR'
                ),
            ))
            //->add('createdAt')
            //->add('updatedAt')
            ->add('profilePhoto',FileType::class,array('data_class'=> null, 'label' => 'Profile Photo'))
            //->add('lastLoggedinAt')
            ->add('clientInfo', ClientInfoType::class,[
                'constraints' => array(new Valid())
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
