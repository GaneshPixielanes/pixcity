<?php

namespace App\Form\Shared;

use App\Entity\UserMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserMediaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name', 'required' => true, 'attr' => ['data-msg' => 'LA PHOTO DE PROFIL EST OBLIGATOIRE']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserMedia::class,
            'type' => ''
        ));

    }
}