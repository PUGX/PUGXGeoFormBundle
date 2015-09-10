<?php

namespace PUGX\GeoFormBundle\Form\Extension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeExtension extends AbstractTypeExtension
{
    private $listener;

    public function __construct(EventSubscriberInterface $listener)
    {
        $this->listener = $listener;
    }

    public function getExtendedType()
    {
        return 'form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['geo_code']) || !$options['geo_code']) {
            return;
        }

        $builder->addEventSubscriber($this->listener);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'geo_code' => false,
            'geo_code_field' => false,
        ));
    }
}
