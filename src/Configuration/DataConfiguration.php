<?php
namespace Lumie\WarehouseManagerApplication\Configuration;

use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneAudioType;
use Lumie\WarehouseManagerApplication\Structure\Enum\HeadphoneConnectionType;
use Lumie\WarehouseManagerApplication\Structure\Enum\KeyboardLayout;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class DataConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('data');
        $treeBuilder
            ->getRootNode()
                    ->children()
                        ->arrayNode('brands')
                            ->arrayPrototype()
                                ->children()
                                    ->integerNode('id')->isRequired()->end()
                                    ->integerNode('qualityRating')
                                        ->min(1)
                                        ->max(5)
                                        ->isRequired()->end()
                                    ->scalarNode('name')->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('products')
                            ->arrayPrototype()
                                ->children()
                                    ->integerNode('id')->isRequired()->end()
                                    ->scalarNode('productNumber')->isRequired()->end()
                                    ->scalarNode('name')->isRequired()->end()
                                    ->integerNode('price')->isRequired()->end()
                                    ->integerNode('brand')->isRequired()->end()
                                    ->enumNode('type')
                                        ->values(['keyboard', 'headphone'])
                                        ->isRequired()->end()
                                    ->enumNode('audioType')
                                        ->values(array_column(HeadphoneAudioType::cases(), 'value'))->end()
                                    ->enumNode('connectionType')
                                        ->values(array_column(HeadphoneConnectionType::cases(), 'value'))->end()
                                    ->enumNode('layout')
                                        ->values(array_column(KeyboardLayout::cases(), 'value'))->end()
                                    ->booleanNode('isRGB')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('warehouses')
                            ->arrayPrototype()
                                ->children()
                                    ->integerNode('id')->isRequired()->end()
                                    ->integerNode('capacity')->min(1)->isRequired()->end()
                                    ->scalarNode('name')->isRequired()->end()
                                    ->scalarNode('address')->isRequired()->end()
                                    ->arrayNode('products')
                                        ->arrayPrototype()
                                            ->children()
                                                ->integerNode('productId')->isRequired()->end()
                                                ->integerNode('quantity')->min(1)->isRequired()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
        ;

        return $treeBuilder;
    }
}