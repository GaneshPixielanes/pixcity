<?php

namespace App\Form\Shared;

use App\Constant\CardStatus;
use App\Entity\Card;
use App\Entity\CardCategory;
use App\Entity\Department;
use App\Entity\Region;
use App\Entity\User;
use App\Form\Shared\AddressType;
use App\Form\Type\SwitchType;
use Doctrine\ORM\EntityManagerInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Valid;

class CardType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionUploadFolder = ($options["upload_folder"])?"/".$options["upload_folder"]:"";
//
//        if(isset($options['project_id']))
//        {
//            dd($options);
//            $draftUrl = "front_pixie_card_creation_draft,".$options['project_id'];
//        }
//        else
//        {
//            $draftUrl = "admin_cards_draft";
//        }
        $builder
            ->add('submit_type', HiddenType::class, array('mapped' => false, 'required' => false))
            ->add('name', TextType::class, array('label' => 'label.cardname'))
            ->add('tagLine', TextType::class, array('label' => 'Tag Line', 'required' => false))
            ->add('thumb', CardMediaType::class, array('label' => 'label.thumb'))
            ->add('masterhead', CardMediaType::class, array('label' => 'label.masterhead'))
            ->add('meta_title', TextType::class, array('label' => 'label.metatitle', 'required' => false))
            ->add('meta_description', TextType::class, array('label' => 'label.metadescription', 'required' => false))
            ->add('slug', TextType::class, array('label' => 'label.slug', 'required' => false));
            if(isset($options['type']) &&($options['type'] == 'admin' || $options['type'] == 'edit') )
            {
                $builder = $builder->add('content', FroalaEditorType::class, array(
                    'required' => true,
                    'saveInterval' => 2000,
                    'saveParam' => 'content',
                    'saveURL' => 'front_pixie_card_creation_draft',
                    'saveMethod' => 'POST',
                    'saveParams' => "{id: 153}",
                    'label' => 'label.content',
                    'attr' => [
                        'rowClass' => 'type-froala'
                    ],
                    'toolbarStickyOffset' =>  70,
                    'toolbarSticky' => true,
                    'imageUploadFolder' => 'uploads/img'.$optionUploadFolder,
                    'imageUploadPath' => 'uploads/img'.$optionUploadFolder
                ));
            }
            else
            {
                $builder = $builder->add('content', FroalaEditorType::class, array(
                    'required' => true,
                    'saveInterval' => 2000,
                    'saveParam' => 'content',
                    'saveURL' => 'front_pixie_card_creation_draft',
                    'saveMethod' => 'POST',
                    'saveParams' => "{id: 153}",
                    'label' => 'label.content',
                    'attr' => [
                        'rowClass' => 'type-froala'
                    ],
                    'toolbarStickyOffset' =>  70,
                    'toolbarSticky' => true,
                    'toolbarInline' => true,
                    'imageUploadFolder' => 'uploads/img'.$optionUploadFolder,
                    'imageUploadPath' => 'uploads/img'.$optionUploadFolder
                ));
            }
            $builder = $builder->add('indexed', SwitchType::class, array('label' => 'label.indexed', 'required' => false))
            ->add('status', ChoiceType::class, array(
                'required' => false,
                'label' => 'label.status',
                'choices'  => CardStatus::getList(),
                'placeholder' => 'label.choose',
            ))

            ->add('infos', CollectionType::class, array(
                'required' => true,
                'entry_type' => CardInfoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'constraints' => array(new Valid()),
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
            ->add('medias', CollectionType::class, array(
                'required' => true,
                'entry_type' => CardMediaType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false
            ))
            ->add('address', AddressType::class, array(
//                'required' => true,
                'constraints' => array(new Valid()),
            ))
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
            'required' => true,
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
            'required' => true,
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
            'data_class' => Card::class,
            'type' => '',
            'upload_folder' => ''
        ));

    }
}