<?php

namespace App\Form\Admin;

use App\Constant\CardProjectStatus;
use App\Constant\PaymentStatus;
use App\Entity\CardCategory;
use App\Entity\CardProject;
use App\Entity\Department;
use App\Entity\Region;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Valid;

class CardProjectType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    { 
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'label.name'))
            ->add('status', ChoiceType::class, array(
                'required' => false,
                'label' => 'label.status',
                'choices'  => CardProjectStatus::getList(),
                'placeholder' => 'label.choose',
            ))
            ->add('pixie', EntityType::class, array(
                'label' => 'label.pixie',
                'class' => User::class,
                'query_builder' => function (EntityRepository $users) {
                    return $users->createQueryBuilder('u')->where('u.pixie IS NOT NULL');
                },
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function (User $value, $key, $index) {
                    return $value->getFirstname()." ".$value->getLastname();
                }
            ))
            ->add('infos', CollectionType::class, array(
                'required' => true,
                'entry_type' => CardProjectInfoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => array(new Valid()),
            ))
            ->add('description', FroalaEditorType::class, array(
                'label' => 'label.description',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'toolbarButtons' => ['bold', 'italic', 'underline'],
                'quickInsertTags' => [],
                'required' => false,
            ))
            ->add('categories', EntityType::class, array(
                'label' => 'label.categories',
                'class' => CardCategory::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'attr' => [
                    'rowClass' => 'multiple-checkboxes'
                ]
            ))
            ->add('minPhotos', IntegerType::class, array('label' => 'label.minphotos'))
            ->add('minVideos', IntegerType::class, array('label' => 'label.minvideos'))
            ->add('minWords', IntegerType::class, array('label' => 'label.minwords'))
            ->add('price', IntegerType::class, array('label' => 'label.price'))
            ->add('deliveryDate', DateType::class, array(
                'label' => 'label.deliverydate',
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'eeee dd MMMM yyyy',
                'attr' => ['rowClass' => 'js-datepicker'],
                'required' => true
            ))
            ->add('comment', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.comment',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'heightMin' => 100,
                'toolbarButtons' => ['bold', 'italic', 'underline'],
                'quickInsertTags' => []
            ))
            ->add('attachments', CollectionType::class, array(
                'required' => true,
                'entry_type' => CardProjectAttachmentType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => array(new Valid()),
            ))
            // ->add('isInterview', ChoiceType::class,[
            //     'choices' => ['Card'=>'0','Interview'=>'1'],
            //     'label' => 'Type de projet'
            // ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));

    }

    protected function addElements(FormInterface $form, Region $region = null) {

        //---------------------------------------------------
        // Regions

        $form->add('region', EntityType::class, array(
            'class' => Region::class,
            'label' => 'label.region',
            'required' => true,
            'data' => $region,
            'placeholder' => 'label.select.region',
            'choice_label' => 'name',
        ));


        //---------------------------------------------------
        // Departments

        // No departments, unless there is a region selected
        $departments = array();
        if ($region) {
            $repoDepartments = $this->em->getRepository(Department::class);
            $departments = $repoDepartments->findBy(['region' => $region]);
        }

        // Add the department field
        $form->add('department', EntityType::class, array(
            'class' => Department::class,
            'label' => 'label.department',
            'required' => false,
            'choices' => $departments,
            'placeholder' => 'label.select.department',
            'choice_label' => 'name',
        ));


        //---------------------------------------------------
        // Pixies

        // No pixies, unless there is a region selected
        $pixies = array();
        if ($region) {
            $repoUsers = $this->em->getRepository(User::class);
            $pixies = $repoUsers->findByPixieRegion($region->getId());
        }

        // Add the pixie field
        $form->add('pixie', EntityType::class, array(
            'class' => User::class,
            'label' => 'label.pixie',
            'required' => false,
            'choices' => $pixies,
            'placeholder' => 'label.select.pixie',
            'choice_label' => function (User $value, $key, $index) {
                return $value->getFirstname()." ".$value->getLastname();
            },
        ));
    }

    public function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        $region = $this->em->getRepository(Region::class)->find($data['region']);

        $this->addElements($form, $region);
    }

    public function onPreSetData(FormEvent $event) {
        $project = $event->getData();
        $form = $event->getForm();

        $region = $project->getRegion() ? $project->getRegion() : null;

        $this->addElements($form, $region);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CardProject::class,
            'validation_groups' => array(
                CardProject::class,
                'determineValidationGroups',
            ),
            'type' => ''
        ));

    }
}