<?php

namespace App\Form\B2B;

use App\Constant\BusinessType;
use App\Entity\ClientInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siret', TextType::class,['label'=>false])
            ->add('business', ChoiceType::class,[
                'expanded' => true,
                'multiple' => false,
                'choices' => BusinessType::getList(),
                'label'=>false
            ])
            ->add('address', TextType::class,['label'=>false])
            ->add('postalCode', TextType::class,['label'=>false])
            ->add('city', TextType::class,['label'=>false])
            ->add('companyCreationDate', DateType::class,[
                'widget' => 'choice',
                'label'=>false,
                'widget' => 'single_text'
            ])
            ->add('mangopayKycFile',HiddenType::class,array('data_class'=> null, 'label' => 'Adresse Preuve 1'))
            ->add('mangopayKycAddr',HiddenType::class,array('data_class'=> null, 'label' => 'Adresse Preuve 2'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientInfo::class,
        ]);
    }
}
