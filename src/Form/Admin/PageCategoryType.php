<?php

namespace App\Form\Admin;

use App\Entity\PageCategory;
use App\Entity\Region;
use App\Form\Type\SwitchType;
use Doctrine\ORM\EntityManagerInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageCategoryType extends AbstractType
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
            ->add('discover', TextType::class, array('label' => 'label.discovertext'))
            ->add('facebook', TextType::class, array('label' => 'label.network.facebook', 'required' => false))
            ->add('meta_title', TextType::class, array('label' => 'label.metatitle', 'required' => false))
            ->add('meta_description', TextType::class, array('label' => 'label.metadescription', 'required' => false))
            ->add('slug', TextType::class, array('label' => 'label.slug', 'required' => false))
            ->add('content', FroalaEditorType::class, array(
                'required' => false,
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'fontFamily' => ['Montserrat'],
            ))
            ->add('indexed', SwitchType::class, array('label' => 'label.indexed', 'required' => false))
            ->add('hidden', SwitchType::class, array('label' => 'label.hidden', 'required' => false))

            ->add('region', EntityType::class, array(
                'class' => Region::class,
                'label' => 'label.region',
                'required' => true,
                'placeholder' => 'label.select.region',
                'choice_label' => 'name',
            ))
            ->add('thumb', PageCategoryMediaType::class, array('label' => 'label.thumb', 'required' => false))
            ->add('background', PageCategoryMediaType::class, array('label' => 'label.backgroundimage', 'required' => false))
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PageCategory::class,
            'type' => ''
        ));

    }
}