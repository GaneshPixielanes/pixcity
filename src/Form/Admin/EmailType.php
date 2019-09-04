<?php

namespace App\Form\Admin;

use App\Entity\Email;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['label' => 'E-mail name'])
            ->add('slug', TextType::class,['label' => 'Slug'])
            ->add('subject',TextType::class,['label' => 'E-mail Subject'])
            ->add('content', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala',
                ],
                'toolbarStickyOffset' =>  70,
                'fontFamily' => ['Monsterrat'],
            ),['label' => 'E-mail Content'])
            ->add('sentTo',ChoiceType::class,[
                'label' => 'Sent To',
                'choices' => [
                    'City Maker' => 'CM',
                    'Voyager' => 'USER',
                    'B2B City Maker' => 'B2BCM',
                    'B2B Client' => 'CLIENT'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Active Status',
                'choices' => [
                    'Active' => 1,
                    'Inactive' => 0
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Email::class,
        ]);
    }
}
