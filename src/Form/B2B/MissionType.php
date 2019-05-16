<?php

namespace App\Form\B2B;

use App\Entity\Client;
use App\Entity\Pack;
use App\Entity\Region;
use App\Entity\UserMission;
use App\Entity\UserPacks;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $regions = [];
        foreach($options['region'] as $region)
        {
            $regions[] = $region->getId();
        }
        $builder
            ->add('title',TextType::class)
            ->add('description', TextareaType::class)
            ->add('client',EntityType::class,[
                'class' => Client::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('referencePack', EntityType::class,[
                'class' => UserPacks::class,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('bannerImage', HiddenType::class)
            ->add('briefFiles', HiddenType::class)
            ->add('missionBasePrice', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('conditionsAgreed', ChoiceType::class,[
                'choices' => ['Yes' => 1, 'No' => 0],
                'multiple' => false,
                'expanded' => false
            ])
            ->add('axaInsurance',ChoiceType::class,[
                'choices' => ['Add Axa insutance' => TRUE],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('generalConditionsBrief')
            ->add('missionAgreedClient', ChoiceType::class,[
                'choices' => ['Client Agreed' => TRUE],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('region', EntityType::class,[
                'class' => Region::class,
                'data' => $options['region'],
                'multiple' => false,
                'expanded' => false,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er) use($regions)
                {
                    return $er->createQueryBuilder('r')->where('r.id IN (:regions)')->setParameter('regions',$regions);
                }
            ])
//            ->add('client')
            ->add('userMissionPayment',UserMissionPaymentType::class)
            ->add('missionMedia',CollectionType::class,[
                'required' => true,
                'entry_type' => MissionMediaType::class,
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
            'data_class' => UserMission::class,
            'region' => null
        ]);
    }
}
