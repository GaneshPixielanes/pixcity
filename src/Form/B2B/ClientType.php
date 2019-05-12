<?php

namespace App\Form\B2B;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'label'=> 'Email'
            ])
            ->add('password', PasswordType::class,[
                'label'=>'Password'
            ])
            ->add('firstName', TextType::class,[
                'label'=>'First Name'
            ])
            ->add('lastName', TextType::class,[
                'label'=>'Last Name'
            ])
            ->add('company', TextType::class,[
                'label'=>'Company'
            ])
            ->add('clientInfo', ClientInfoType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
