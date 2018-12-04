<?php

namespace PUGX\GeoFormBundle\Form\Extension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeExtension extends AbstractTypeExtension
{
    private $listener;

    public function __construct(EventSubscriberInterface $listener)
    {
        $this->listener = $listener;
    }

    public function getExtendedType(): string
    {
        return FormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!isset($options['geo_code']) || !$options['geo_code']) {
            return;
        }

        $builder->addEventSubscriber($this->listener);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'geo_code' => false,
            'geo_code_field' => false,
        ]);
    }
}
