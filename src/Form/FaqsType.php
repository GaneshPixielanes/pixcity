<?php

namespace App\Form;

use App\Entity\Faqs;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => 'label.title'))
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Client' => 'CLIENT',
                    'CM' => 'CM'
                ],
            ])
            ->add('description', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'fontFamily' => ['Montserrat'],
            ))
            ->add('subcategory', ChoiceType::class, [
                'choices' => [
                    'QUESTIONS JURIDIQUES' => '0',
                    'QUESTIONS GENERALES' => '1',
                    'LES DEVIS, PACKS ET MISSIONS' => '2',

                    'LE PAIEMENT DU CITY-MAKER' => '3',
                    'COMMENT DEVENIR CITY-MAKER SUR PIX.CITY SERVICES ? ' => '4',

                    'LE PAIEMENT COTE CLIENT' => '5',
                    'COMMENT DEVENIR CLIENT SUR PIX.CITY SERVICES ? ' => '6'
                ],
            ])
           // ->add('createdAt')
           // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faqs::class,
        ]);
    }
}
