<?php

namespace App\Form\B2B;

use App\Entity\Client;
use App\Entity\Pack;
use App\Entity\Region;
use App\Entity\UserMission;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'query_builder' => function(EntityRepository $er) use($regions)
                {
                  return $er->createQueryBuilder('r')->where('r.id IN (:regions)')->setParameter('regions',$regions);
                }
            ])
//            ->add('client')
            ->add('userMissionPayment',UserMissionPaymentType::class)
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
