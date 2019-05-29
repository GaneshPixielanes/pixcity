<?php

namespace App\Form\B2B;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $requiredOnCreation = true;
        if(isset($options["type"]) && $options["type"] == "edit"){
            $requiredOnCreation = false;
        }


        $builder
            ->add('profilePhoto',HiddenType::class,['label'=>false])
            ->add('email',EmailType::class,[
                'label'=> 'Email'
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'label.password'),
                'second_options' => array('label' => 'label.repeatpassword'),
                'required' => $requiredOnCreation
            ))
            ->add('firstName', TextType::class,[
                'label'=>'First Name'
            ])
            ->add('lastName', TextType::class,[
                'label'=>'Last Name'
            ])
            ->add('company', TextType::class,[
                'label'=>'Company'
            ])
            ->add('clientInfo', ClientInfoType::class,[
                'constraints' => array(new Valid())
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'type' => ''
        ]);
    }
}
