<?php

namespace PUGX\GeoFormBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PUGXGeoFormExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $this->processConfiguration($configuration, $configs);
        $container->setParameter('pugx_geo_form.region', $configs[0]['region']);
        $container->setParameter('pugx_geo_form.useSsl', $configs[0]['useSsl']);
        $container->setParameter('pugx_geo_form.names', $configs[0]['names']);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
