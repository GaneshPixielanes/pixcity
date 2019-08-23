<?php

namespace App\Form;

use App\Entity\BlogPost;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description', FroalaEditorType::class, array(
                'label' => 'label.description',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70
            ))
            ->add('content', FroalaEditorType::class, array(
                'label' => 'label.content',
                'attr' => [
                    'rowClass' => 'type-froala'
                ],
                'toolbarStickyOffset' =>  70
            ))
            ->add('bannerImage', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('imageAlt')
            ->add('metaTitle')
            ->add('metaDesc')
            ->add('postStatus', ChoiceType::class,[
                'choices' => [
                    'Active' => '1',
                    'Draft'=> '0'
                ]
            ])
            ->add('position')
            ->add('slug')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('deleted')
            ->add('category')
           // ->add('createdBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
