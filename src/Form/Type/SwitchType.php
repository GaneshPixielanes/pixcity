<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SwitchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setData(isset($options['data']) ? $options['data'] : false);
        $builder->addViewTransformer(new BooleanToStringTransformer($options['value']));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'value' => $options['value'],
            'checked' => null !== $form->getViewData(),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $emptyData = function (FormInterface $form, $viewData) {
            return $viewData;
        };

        $resolver->setDefaults(array(
            'value' => '1',
            'empty_data' => $emptyData,
            'compound' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'switch';
    }
}
