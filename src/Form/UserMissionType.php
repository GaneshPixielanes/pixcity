<?php

namespace App\Form;

use App\Entity\UserMission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserMissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('title')
            ->add('bannerImage',FileType::class,array('data_class'=> null, 'label' => 'BannerImage'))
            ->add('briefFiles')
            ->add('missionBasePrice')
           // ->add('createdAt')
           // ->add('updatedAt')
            ->add('status')
            ->add('dueDate', DateType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
            ])
            ->add('conditionsAgreed')
            ->add('axaInsurance')
            ->add('generalConditionsBrief')
            ->add('missionAgreedClient')
            ->add('cancelReason')
            ->add('cancelledBy')
            ->add('user',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('client',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('referencePack')
            //->add('userMissionPayment')
            ->add('region')
           // ->add('log')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserMission::class,
        ]);
    }
}
