<?php

namespace App\Form\Shared;

use App\Entity\UserLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLinkType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'label.type',
                'choices'  => array(
                    'label.network.facebook' => 'facebook',
                    'label.network.twitter' => 'twitter',
                    'label.network.instagram' => 'instagram',
                    'label.network.youtube' => 'youtube',
                    'label.network.blog' => 'blog',
                    'label.network.other' => 'www',
                ),
            ))
            ->add('url', UrlType::class, array('label' => 'label.url','required'=>true, 'attr' => ['placeholder'=>'label.url']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserLink::class,
            'type' => ''
        ));


    }
}