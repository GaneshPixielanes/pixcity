<?php

namespace App\Form\Admin;

use App\Entity\Page;
use App\Entity\Slider;
use App\Form\Type\SwitchType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name'))
            ->add('meta_title', TextType::class, array('label' => 'label.metatitle'))
            ->add('meta_description', TextType::class, array('label' => 'label.metadescription'))
            ->add('slug', TextType::class, array('label' => 'label.slug', 'required' => false))
            ->add('slider', EntityType::class, array(
                'class' => Slider::class,
                'label' => 'label.slider',
                'required' => false,
                'placeholder' => 'label.select.slider',
                'choice_label' => 'name',
            ))
            ->add('content', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'fontFamily' => ['Monsterrat'],
            ))
            ->add('indexed', SwitchType::class, array('label' => 'label.indexed', 'required' => false))
            ->add('platform', ChoiceType::class, [
                 'choices' => [
                     'B2C' => 0,
                     'B2B' => 1
                 ]
             ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Page::class,
            'type' => ''
        ));

    }
}