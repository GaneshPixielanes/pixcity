<?php

namespace App\Form\Admin;

use App\Entity\MenuItem;
use App\Entity\Page;
use App\Form\Type\SwitchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name', 'required' => false))
            ->add('link', TextType::class, array('label' => 'label.link', 'required' => false))
            ->add('page', EntityType::class, array(
                'class' => Page::class,
                'label' => 'label.page',
                'required' => false,
                'placeholder' => 'label.select.page',
                'choice_label' => 'name',
            ))
            ->add('blank', SwitchType::class, array('label' => 'label.openblank', 'required' => false))
            ->add('position', IntegerType::class, array('label' => 'label.position', 'required' => false))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MenuItem::class,
            'type' => ''
        ));

    }
}