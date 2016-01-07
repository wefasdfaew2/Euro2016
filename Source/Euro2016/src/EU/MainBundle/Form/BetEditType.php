<?php

namespace EU\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BetEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('game')
            ->remove('pot')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

    public function getParent()
    {
        return new BetType();
    }
}
