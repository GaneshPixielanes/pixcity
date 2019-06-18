<?php

namespace App\Form\B2B;

use App\Entity\ClientMissionProposal;
use App\Entity\ClientMissionProposalMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientMissionProposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description',TextareaType::class,['label' => false,
                'attr' => ['placeholder' => 'Write your proposal here', 'rows' => '15', 'maxlength' => 1200]
            ])
            ->add('medias', CollectionType::class,[
                'required' => true,
                'entry_type' => ClientMissionProposalMediaType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
                'by_reference' => false,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClientMissionProposal::class,
            'pack' => null
        ]);
    }
}
