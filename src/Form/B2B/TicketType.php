<?php

namespace App\Form\B2B;

use App\Entity\Client;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('initiator',TextType::class)
            ->add('Object',TextType::class,['label' => 'subject'])
            ->add('client',EntityType::class,[
                'label' => 'client',
                'class' => Client::class,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('cm',EntityType::class,[
                'label' => 'City Maker',
                'class' => User::class,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => false,

            ])
            ->add('template_type',TextType::class)
            ->add('messages', MessageType::class,[
                'constraints' => array(new Valid())
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
