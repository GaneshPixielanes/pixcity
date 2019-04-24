<?php

namespace App\Form\Shared;

use App\Constant\BillingMethod;
use App\Constant\CompanyStatus;
use App\Entity\UserPixieBilling;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class UserPixieBillingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('status', ChoiceType::class, array(
                'label' => 'label.status',
                'choices'  => CompanyStatus::getList(),
            ))
            ->add('companyName', TextType::class, array('label' => 'label.company.name'))
            ->add('firstname', TextType::class, array('label' => 'label.firstname'))
            ->add('lastname', TextType::class, array('label' => 'label.lastname'))
            ->add('tva', TextType::class, array(
                'label' => 'label.tva',
                'required' => false,
            ))

            ->add('address', AddressType::class, array(
                'required' => true,
                'constraints' => array(new Valid()),
            ))

            ->add('phone', TextType::class, array('label' => 'label.phone'))
            ->add('billingIban', TextType::class, array('label' => 'label.billing.iban',"attr"=>["class"=>"form-control", "required"=>"required"],  "required"=>true))
            ->add('billingBic', TextType::class, array('label' => 'label.billing.bic',"attr"=>["class"=>"form-control", "required"=>"required"], "required"=>true))
            ->add('billingType', ChoiceType::class, array(
                'label' => 'label.billing.type',
                'choices' => BillingMethod::getList(),
            ))
        ;


        if("edit" !== $options["type"]) {

            $builder
                ->add('billingName', TextType::class, array('label' => 'label.billing.name'))
                ->add('billingCountry', CountryType::class, array('label' => 'label.country', "preferred_choices" => array('FR')))
                ->add('rib', FileType::class, array(
                    'label' => 'label.billing.rib',
                    'help' => 'help.upload.rib',
                    'required' => false,
                    'preview' => 'ribUrl',
                    'download' => true,
                ));

        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserPixieBilling::class,
            'type' => ''
        ));


    }
}