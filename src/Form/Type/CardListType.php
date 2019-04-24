<?php

namespace App\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardListType extends AbstractType implements DataTransformerInterface
{
    private $em;
    private $class;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->class = $options["class"];
        $builder->addViewTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => false,
            'invalid_message' => 'The selected item does not exist',
        ]);

        $resolver->setRequired([
            'class',
            'route',
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['route'] = $options["route"];
    }

    public function getBlockPrefix()
    {
        return 'cardlist';
    }

    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data)
    {
        $repo = $this->em->getRepository($this->class);
        $model = $repo->findBy(["id" => $data]);
        return $model;
    }

}
