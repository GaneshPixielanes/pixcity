<?php

namespace App\Form;

use App\Entity\Royalties;
use App\Entity\User;
use App\Entity\UserMission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class RoyaltiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('base_price')
            ->add('tax')
            ->add('tax_value')
            ->add('total_price')
            ->add('invoice_path')
            ->add('payment_type')
            ->add('status', ChoiceType::class, array(
                'label' => 'label.status',
                'choices'  => [
                    'Pending' => 'pending',
                    'Success' => 'success',
                ],
                'placeholder' => 'label.choose',
                'required' => false,
            ))
            ->add('bank_details')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('cycle')
            ->add('transfer_id')
            ->add('transfer_date')
            ->add('payout_id')
            ->add('payout_date')
            ->add('mission', EntityType::class, array(
                'class' => UserMission::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.title', 'ASC');
                },
                'label' => 'label.title',
                'required' => false,
                'placeholder' => 'label.title',
                'choice_label' => 'title',
            ))
            ->add('cm', EntityType::class, array(
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.firstname IS NOT NULL')
                        ->orderBy('u.firstname', 'ASC');
                },
                'label' => 'CM',
                'required' => false,
                'placeholder' => 'CM',
//                'choice_label' => '',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Royalties::class,
        ]);
    }
}
