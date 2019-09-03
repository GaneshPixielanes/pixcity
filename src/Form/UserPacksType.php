<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserPacks;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPacksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$regions = [];
        foreach($options['regions'] as $region)
        {
            $regions[] = $region->getId();
        }*/
        //dd($regions);
        $builder
            ->add('title')
            ->add('description', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'fontFamily' => ['Montserrat'],
            ))
            ->add('bannerImage',HiddenType::class,array('data_class'=> null, 'label' => 'Image bannière'))
            ->add('userBasePrice')
            ->add('marginValue',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('totalPrice',TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('packPhotos',HiddenType::class,array('data_class'=> null, 'label' => 'Pack Photos'))
            //->add('createdAt')
            //->add('updatedAt')
           // ->add('deletedAt')
            ->add('active')
            //->add('deleted')
            //->add('marginPercentage')
            ->add('user',EntityType::class, array(
                'class' => User::class,
                'choice_label' => 'firstname',
                'label'=>'CM'
            ))
           // ->add('packSkill')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserPacks::class,
            'regions' => null
        ]);
    }
}
