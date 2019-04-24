<?php

namespace App\Form\Type;

use App\Entity\Notifications;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message',TextareaType::class,['label'=>'Message'])
            ->add('comments', TextareaType::class,['label' => 'Comment(s)'])
            ->add('sent_to', ChoiceType::class,['label'=>'To','choices'=>['All Users'=>true,'All Pixies'=>false,'All Voyagers'=>false]]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Notifications::class,
        ]);
    }
}
