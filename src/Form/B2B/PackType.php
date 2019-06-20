<?php

namespace App\Form\B2B;

use App\Entity\Skill;
use App\Entity\UserPacks;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('packSkill', EntityType::class, array(
                'label' => 'skills',
                'class' => Skill::class,
                'choice_label' => 'name',
                'expanded' => true
            ))
            ->add('title',TextType::class,['label' => false,'attr' => [
                'maxlength' => '64',
                'class' => 'form-control bg-lightwhite pack-title',
                'placeholder' => 'Je m’occupe de vos réseaux sociaux '
            ]])
            ->add('description',TextareaType::class,['label' => 'Description','attr' => [
                'maxlength' => '1200',
                'class' => 'form-control bg-lightwhite fz-14 pack-description',
                'placeholder' => 'Décrivez de la manière la plus précise possible ce que vous allez faire pour votre client',
                'cols' => '30',
                'rows' => '10'
            ]])
            ->add('userBasePrice',IntegerType::class,[
                'label' => false,
                'attr' => [
                    'class' => 'form-control fz-16 font-weight-bold bg-lightwhite',
                    'aria-label' => 'Pack price',
                    'aria-describedby' => "pack-price"
                ]
            ])
            ->add('bannerImage',HiddenType::class,['label' => false])
            ->add('userPackMedia',CollectionType::class,[
                'required' => true,
                'entry_type' => UserPackMediaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPacks::class,
        ]);
    }
}
