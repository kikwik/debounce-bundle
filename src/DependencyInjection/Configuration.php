<?php

namespace Kikwik\DebounceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('kikwik_debounce');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('api_key')->defaultValue('')->cannotBeEmpty()->end()
                ->arrayNode('safe_codes')->integerPrototype()->end()->defaultValue([4,5,7,8])->end()
            ->end()
        ;

        return $treeBuilder;
    }

}