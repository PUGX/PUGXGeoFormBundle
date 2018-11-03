<?php

namespace PUGX\GeoFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pugx_geo_form');
        $rootNode
            ->children()
                ->scalarNode('http_adapter')
                    ->defaultValue('Http\Adapter\Guzzle6\Client')
                ->end()
                ->scalarNode('region')
                    ->validate()
                    ->ifNull()
                    ->thenInvalid('You should specify a region for geocoding services')
                    ->end()
                ->end()
                ->booleanNode('useSsl')
                    ->defaultValue(false)
                    ->treatNullLike(false)
                ->end()
                ->arrayNode('names')
                    ->addDefaultsIfNotSet()
                    ->treatNullLike([
                        'lat' => 'latitude',
                        'lng' => 'longitude',
                    ])
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
