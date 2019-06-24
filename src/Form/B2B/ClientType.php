<?php

namespace App\Form\B2B;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('profilePhoto',HiddenType::class,['label'=>'Add Logo'])
            ->add('email',EmailType::class,[
                'label'=> 'Email'
            ])
            ->add('plainPassword', PasswordType::class, array(
                'required' => $requiredOnCreation,
                'label'=>false
            ))
            ->add('firstName', TextType::class,[
                'label'=>'First Name',
                'label'=>false
            ])
            ->add('lastName', TextType::class,[
                'label'=>'Last Name',
                'label'=>false
            ])
            ->add('company', TextType::class,[
                'label'=>'Company',
                'label'=>false
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
