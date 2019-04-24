<?php

namespace App\Form\Shared;

use App\Entity\Region;
use App\Entity\UserPixie;
use App\Entity\UserPixieBilling;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class UserPixieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('likeText', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.whatilike',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'toolbarSticky' => false,
                'toolbarButtons' => ['bold', 'italic', 'underline', 'strikeThrough'],
                'quickInsertTags' => []
            ))
            ->add('dislikeText', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.whatidontlike',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'toolbarSticky' => false,
                'toolbarButtons' => ['bold', 'italic', 'underline', 'strikeThrough'],
                'quickInsertTags' => []
            ))
            ->add('billing', UserPixieBillingType::class, array(
                'required' => true,
                'constraints' => array(new Valid()),
                'type' => $options["type"]
            ))
            ->add('regions', EntityType::class, array(
                'label' => 'label.regions',
                'class' => Region::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'attr' => [
                    'rowClass' => 'multiple-checkboxes'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserPixie::class,
            'type' => ''
        ));


    }
}