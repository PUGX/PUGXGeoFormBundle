<?php

namespace PUGX\GeoFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * PUGXGeoFormBundle configuration structure.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pugx_geo_form', 'array');
        $rootNode
            ->children()
                ->scalarNode('region')
                    ->validate()
                    ->ifNull()
                    ->thenInvalid('You should specify a region for geocoding services')
                    ->end()
                ->end()
                ->scalarNode('useSsl')
                    ->validate()
                    ->ifNull()
                    ->thenInvalid('You should specify if enable SSL for geocoding services')
                    ->end()
                ->end()
                ->arrayNode('names')
                    ->children()
                        ->scalarNode('lat')
                            ->cannotBeEmpty()
                            ->defaultValue('latitude')
                        ->end()
                        ->scalarNode('lng')
                            ->cannotBeEmpty()
                            ->defaultValue('longitude')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
