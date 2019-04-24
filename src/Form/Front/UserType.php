<?php

namespace App\Form\Front;

use App\Entity\CardCategory;
use App\Entity\Region;
use App\Entity\User;
use App\Form\Shared\UserAvatarType;
use App\Form\Shared\UserLinkType;
use App\Form\Shared\UserOptinType;
use App\Form\Shared\UserPixieType;
use App\Form\Shared\UserMediaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
        $fromSocialLogin = false;
        if(!isset($options["type"]) || "social" === $options["type"]){
            $fromSocialLogin = true;
        }

        $userLoggedIn = "loggedin" === $options["type"];
        $isEdit = "edit" === $options["type"];

        if(!$userLoggedIn){
            $builder
                ->add('avatar', UserMediaType::class, array(
                    'label' => 'label.avatar',
                    'required' => ($options["pixie"])?true:false,
                    'attr' => ['data-msg' => 'Please fill this field']
                ))
                ->add('firstname', TextType::class, array('label' => 'label.firstname', 'required' => true))
                ->add('lastname', TextType::class, array('label' => 'label.lastname', 'required' => true))
                ->add('phone', TelType::class, array('label' => 'label.phone', 'required' => ($options["pixie"])?true:false))
                ->add('birthdate', BirthdayType::class, array(
                    'label' => 'label.birthdate',
                    'format' => 'dd MMMM yyyy',
                    'required' => true,
                    'years' => range(date('Y'), 1920),
                    'placeholder' => array(
                        'year' => 'label.year', 'month' => 'label.month', 'day' => 'label.day',
                    )
                ))
                ->add('birthLocation', EntityType::class, array(
                    'class' => Region::class,
                    'label' => 'label.birth_place',
                    'help' => 'help.birth_place',
                    'required' => true,
                    'placeholder' => 'label.select.region',
                    'choice_label' => 'name',
                ))
                ->add('currentLocation', TextType::class, array(
                    'label' => 'label.currentLocation',
                    'attr' => ['placeholder' => 'label.zipcode'],
                ))
                ->add('gender', ChoiceType::class, array(
                    'label' => 'label.gender',
                    'multiple' => false,
                    'expanded' => true,
                    'choices'  => array(
                        'label.male' => 'male',
                        'label.female' => 'female'
                    ),
                    'attr' => [
                        'rowClass' => 'radios'
                    ],
                    'required' => true,
                ));
        }


        $builder->add('favoriteCategories', EntityType::class, array(
                'label' => 'label.categories',
                'class' => CardCategory::class,
                'multiple' => true,
                'required' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'attr' => [
                    'rowClass' => 'multiple-checkboxes'
                ]
            ))
        ;

        //--------------------------------------------
        // If user come from social login
        //--------------------------------------------

        if(!$fromSocialLogin && !$userLoggedIn){
            $builder
                ->add('email', EmailType::class, array('label' => 'label.email'))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'required' => ($isEdit)?false:true,
                    'first_options'  => array('label' => 'label.password'),
                    'second_options' => array('label' => 'label.repeatpassword')
                ));
        }

        if($isEdit){
            $builder
                ->add('oldPassword', PasswordType::class, array(
                    'label' => 'label.oldpassword',
                    'required' => true
                ))
                ->add('optin', UserOptinType::class, array(
                    'required' => true,
                    'constraints' => array(new Valid()),
                ));
        }


        //--------------------------------------------
        // For the Pixie register form
        //--------------------------------------------

        if($options["pixie"]){

            $builder
                ->add('links', CollectionType::class, array(
                    'required' => true,
                    'entry_type' => UserLinkType::class,
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'constraints' => array(new Valid()),
                ))
                ->add('pixie', UserPixieType::class, array(
                    'required' => true,
                    'constraints' => array(new Valid()),
                    'type' => $options["type"]
                ));

        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'data_class' => User::class,
            'validation_groups' => array(
                User::class,
                'determineValidationGroups',
            ),
            'type' => '',
            'pixie' => false
        ));


    }
}