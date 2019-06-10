<?php

namespace App\Form\Admin;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CardExtractionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class, [
            'date_label' => 'Start On','widget' => 'single_text',
            'label' => 'label.startDate','attr' => ['class' => 'startDate-datepicker'],'html5' => false,
        ])
        ->add('endDate', DateTimeType::class, [
            'date_label' => 'End On','widget' => 'single_text',
            'label' => 'label.endDate','attr' => ['class' => 'endDate-datepicker'],'html5' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
