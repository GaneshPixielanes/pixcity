<?php

namespace App\Form\Shared;

use App\Entity\CardMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CardMediaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name','attr' => ['class' => 'media-name']))
            ->add('hashtags', CollectionType::class, array(
                'required' => true,
                'entry_type' => HashtagType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'label' => false
            ))
            ->add('description',TextareaType::class,['required'=>false,'attr'=>['class' => 'form-control media-description']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CardMedia::class,
            'type' => ''
        ));

    }
}