<?php

namespace App\Form\B2B;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class,['label' => false,'mapped' => false,])
            ->add('type')
            ->add('status')
            ->add('attachment',FileType::class,[
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'label' => false
            ])
            ->add('auto_mail')
            ->add('created_at')
            ->add('updated_at')
            ->add('ticket')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
