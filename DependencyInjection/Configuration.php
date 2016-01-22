<?php

namespace PUGX\GeoFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * FrameworkExtraBundle configuration structure.
 *
 * @author Henrik Bjornskov <hb@peytz.dk>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
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
            ->end();

        return $treeBuilder;
    }
}
