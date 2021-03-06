<?php

declare(strict_types=1);

namespace Loevgaard\DandomainPeriodBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('loevgaard_dandomain_period');

        $rootNode
            ->children()
                ->scalarNode('format')
                    ->defaultValue('P%d')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is the format for the period id in Dandomain. Use %d to insert the computed period number')
                ->end()
                ->integerNode('ahead')
                    ->defaultValue('1')
                    ->info('Number of periods to create ahead')
                ->end()
                ->scalarNode('interval')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('A \DateInterval compatible string that tells how long every period should be, i.e. P6W for 6 weeks')
                ->end()
                ->integerNode('start_year')
                    ->defaultValue(date('Y'))
                    ->info('Set the start year for when period calculations will start. If in doubt, just use the year you are in')
                ->end()
                ->scalarNode('start_day')
                    ->defaultValue('monday')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Can be either monday, tuesday, wednesday, thursday, friday, saturday, sunday')
                ->end()
                ->scalarNode('import_dir')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Where to save imports. Must be a public directory')
                ->end()
                ->scalarNode('import_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is the URL of the directory (public accessible) where imports are stored')
                ->end()
                ->scalarNode('dandomain_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is the base URL of your shop, i.e. https://yourshop.dk')
                ->end()
                ->scalarNode('dandomain_username')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is a valid username for your shop admin')
                ->end()
                ->scalarNode('dandomain_password')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('This is a valid password for your shop admin')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
