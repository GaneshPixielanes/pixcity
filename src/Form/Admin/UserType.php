<?php

namespace App\Form\Admin;

use App\Entity\CardCategory;
use App\Entity\Region;
use App\Entity\Skill;
use App\Entity\User;
use App\Form\Shared\UserLinkType;
use App\Form\Shared\UserMediaType;
use App\Form\Shared\UserPixieType;
use App\Form\Type\SwitchType;
use App\Form\Admin\UserRegistrationCheckType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Valid;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $requiredOnCreation = true;
        if(!isset($options["type"]) || "editFromAdmin" === $options["type"]){
            $requiredOnCreation = false;
        }

        $builder
            ->add('active', SwitchType::class, array('label' => 'label.active', 'required' => false))
            ->add('visible', SwitchType::class, array('label' => 'label.visible', 'required' => false))
            ->add('avatar', UserMediaType::class, array('label' => 'label.avatar', 'required' => true))
            ->add('email', EmailType::class, array('label' => 'label.email'))
            ->add('firstname', TextType::class, array('label' => 'label.firstname'))
            ->add('lastname', TextType::class, array('label' => 'label.lastname'))
            ->add('phone', TextType::class, array('label' => 'label.phone', 'required' => false))
            ->add('birthdate', BirthdayType::class, array('label' => 'label.birthdate', 'format' => 'dd MMMM yyyy'))
            ->add('birthLocation', EntityType::class, array(
                'class' => Region::class,
                'label' => 'label.region',
                'required' => true,
                'placeholder' => 'label.select.region',
                'choice_label' => 'name',
            ))
            ->add('currentLocation', TextType::class, array('label' => 'label.currentLocation'))
            ->add('gender', ChoiceType::class, array(
                'label' => 'label.gender',
                'multiple' => false,
                'expanded' => true,
                'choices'  => array(
                    'label.male' => 'male',
                    'label.female' => 'female'
                ),
            ))
            ->add('roles', ChoiceType::class, array(
                'label' => 'label.role',
                'multiple' => true,
                'expanded' => true,
                'choices'  => array(
                    'label.user' => 'ROLE_USER',
                    'label.pixie' => 'ROLE_PIXIE'
                ),
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'label.password'),
                'second_options' => array('label' => 'label.repeatpassword'),
                'required' => $requiredOnCreation
            ))
            ->add('links', CollectionType::class, array(
                'required' => false,
                'entry_type' => UserLinkType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => array(new Valid()),
            ))
            ->add('pixie', UserPixieType::class, array(
                'required' => false,
                'constraints' => array(new Valid()),
            ))
            ->add('userRegistrationCheck', UserRegistrationCheckType::class, [
                'required' => false
            ])
            ->add('favoriteCategories', EntityType::class, array(
                'label' => 'label.categories',
                'class' => CardCategory::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'attr' => [
                    'rowClass' => 'multiple-checkboxes'
                ]
            ));
        if((!isset($options["type"]) || "editFromAdmin" === $options["type"]) && (!isset($options["roleSet"]) || "b2b" === $options["roleSet"])) {
            $builder
                ->add('b2bCmApproval', ChoiceType::class, [
                    'choices' => [
                        'Yes' => 1,
                        'No' => 0
                    ],
                ])
                ->add('userSkill', EntityType::class, array(
                    'label' => 'label.skills',
                    'class' => Skill::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
                ->add('userRegion', EntityType::class, array(
                    'label' => 'label.regions',
                    'class' => Region::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
            ;
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'validation_groups' => array(
                User::class,
                'determineValidationGroups',
            ),
            'type' => '',
            'roleSet' => 'b2c',
        ));


    }
}