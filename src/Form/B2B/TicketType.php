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
        $emails = $options['emails'];

        $builder
            ->add('initiator',TextType::class)
            ->add('Object',TextType::class,['label' => 'subject'])
            ->add('client',EntityType::class,[
                'class' => Client::class,
                'multiple' => false,
                'expanded' => false,
                'label' => 'label.email',
                'choice_label' => 'email',
                'query_builder' => function(EntityRepository $er) use($emails)
                {
                    return $er->createQueryBuilder('c')
                        ->where('c.id IN (:user)')->setParameter('user',$emails);
                }
            ])

            ->add('cm',EntityType::class,[
                'class' => User::class,
                'multiple' => false,
                'expanded' => false,
                'label' => 'label.email',
                'choice_label' => 'email',
                'query_builder' => function(EntityRepository $er) use($emails)
                {
                    return $er->createQueryBuilder('c')
                        ->where('c.id IN (:user)')->setParameter('user',$emails);
                }
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
            'emails' => []
        ]);
    }
}
