<?php

namespace FremartecBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', 'choice', array('choices' => array(
                'hom1' => 'Curriculum Empresa',
                'hom2' => 'Home',
                'com1' => 'Producto a',
                'com2' => 'Producto b',
                'com3' => 'Producto c',
                'ing1' => 'Manual a',
                'ing2' => 'Manual b',
                'ing3' => 'Manual c',
                'con1' => '--',
                'con2' => '--'
               )))
            ->add('description')
            ->add('file')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FremartecBundle\Entity\Documents'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document';
    }
}
