<?php

namespace App\Form\B2B;

use App\Entity\Skill;
use App\Entity\UserPacks;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('title',TextType::class,['label' => 'Title'])
            ->add('description',TextareaType::class,['label' => 'Description'])
            ->add('userBasePrice',TextType::class,['label' => 'Price €'])
            ->add('bannerImage',TextType::class,['label' => 'Cover Image'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPacks::class,
        ]);
    }
}
