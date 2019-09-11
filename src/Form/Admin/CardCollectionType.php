<?php

namespace App\Form\Admin;

use App\Entity\Card;
use App\Entity\CardCollection;
use App\Entity\User;
use App\Form\Type\CardListType;
use App\Form\Type\SwitchType;
use App\Form\Type\TypeaheadType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CardCollectionType extends AbstractType
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
            ->add('public', SwitchType::class, array('label' => 'label.public', 'required' => false))
            ->add('description', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70,
                'required' => false,
                'fontFamily' => ['Montserrat'],
            ))
            ->add('user', TypeaheadType::class, array(
                'label' => 'label.user',
                'required' => false,
                'class' => User::class,
                'attr' => [
                    'placeholder' => 'label.typeahead.user'
                ],
                'route' => $options["userTypeaheadRoute"]
            ))
            ->add('cards', CardListType::class, array(
                'label' => 'label.cards',
                'class' => Card::class,
                'route' => $options["cardTypeaheadRoute"]
            ))
        ;

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CardCollection::class,
            'type' => '',
            'userTypeaheadRoute' => '',
            'cardTypeaheadRoute' => '',
        ));

    }
}