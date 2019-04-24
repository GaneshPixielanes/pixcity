<?php

namespace App\Form\Admin;

use App\Entity\SliderMedia;
use App\Entity\SliderSlide;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SliderSlideType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FroalaEditorType::class, array('label' => 'label.name',
                'height'=>50,
                'toolbarButtons' => ["bold", "italic", "underline", "strikeThrough","fontFamily","fontSize"],
                'required'=>false,'attr' => [
                    'rowClass' => 'type-froala'
                ]))
            ->add('description', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.description',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                //'toolbarButtons' => ['bold', 'italic', 'underline', 'strikeThrough'],
                'quickInsertTags' => []
            ))
            ->add('image', SliderMediaType::class, array('label' => 'label.image', 'required' => false))
            ->add('background', SliderMediaType::class, array('label' => 'label.backgroundimage', 'required' => false))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SliderSlide::class,
            'type' => ''
        ));

    }
}