<?php

namespace App\Form\Admin;

use App\Entity\CardCategory;
use App\Entity\CardWall;
use App\Entity\Department;
use App\Entity\Region;
use App\Form\Type\SwitchType;
use Doctrine\ORM\EntityManagerInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CardWallType extends AbstractType
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
            ->add('meta_title', TextType::class, array('label' => 'label.metatitle', 'required' => false))
            ->add('meta_description', TextType::class, array('label' => 'label.metadescription', 'required' => false))
            ->add('slug', TextType::class, array('label' => 'label.slug', 'required' => false))
            ->add('content', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70
            ))
            ->add('indexed', SwitchType::class, array('label' => 'label.indexed', 'required' => false))

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
            'required' => false,
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
            'data_class' => CardWall::class,
            'type' => ''
        ));

    }
}