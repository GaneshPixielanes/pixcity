<?php

namespace App\Form\Shared;

use App\Entity\UserOptin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserOptinType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newsletter', CheckboxType::class, array('label' => 'label.optin_newsletter', 'required' => false))
            ->add('pixieCardProjectReceived', CheckboxType::class, array('label' => 'label.optin_pixie_card_demand', 'required' => false))
            ->add('pixieCardStatusUpdated', CheckboxType::class, array('label' => 'label.optin_pixie_card_status', 'required' => false))
            ->add('lastCardsPublished', CheckboxType::class, array('label' => 'label.optin_cards_published', 'required' => false))
            ->add('lastCardsPublishedFavoritesRegions', CheckboxType::class, array('label' => 'label.optin_cards_published_from_favorites_regions', 'required' => false))
            ->add('cardsOfTheMonth', CheckboxType::class, array('label' => 'label.optin_cards_month', 'required' => false))
            ->add('myPixiesActivity', CheckboxType::class, array('label' => 'label.optin_my_pixies_activity', 'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserOptin::class,
            'type' => ''
        ));


    }
}