<?php

namespace App\Form;

use App\Entity\UserPacks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPacksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('bannerImage',FileType::class,array('data_class'=> null, 'label' => 'BannerImage'))
            ->add('userBasePrice')
            ->add('marginValue',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('totalPrice',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('packPhotos',FileType::class,array('data_class'=> null, 'label' => 'Pack Photos'))
            //->add('createdAt')
            //->add('updatedAt')
           // ->add('deletedAt')
            ->add('active')
            //->add('deleted')
            //->add('marginPercentage')
            ->add('user',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
           // ->add('packSkill')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPacks::class,
        ]);
    }
}
