<?php

namespace MasterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HistoricoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('dataPassagem')
            ->add('idpedido')
            ->add('idpara')
            ->add('tipoHistorico')
            ->add('idmensagem')
            ->add('idde')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MasterBundle\Entity\Historico'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'MasterBundle_historico';
    }
}
