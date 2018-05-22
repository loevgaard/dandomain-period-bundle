<?php

declare(strict_types=1);

namespace Loevgaard\DandomainPeriodBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LoevgaardDandomainPeriodExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('loevgaard_dandomain_period.format', $config['format']);
        $container->setParameter('loevgaard_dandomain_period.ahead', $config['ahead']);
        $container->setParameter('loevgaard_dandomain_period.interval', $config['interval']);
        $container->setParameter('loevgaard_dandomain_period.start_year', $config['start_year']);
        $container->setParameter('loevgaard_dandomain_period.start_day', $config['start_day']);
        $container->setParameter('loevgaard_dandomain_period.import_dir', $config['import_dir']);
        $container->setParameter('loevgaard_dandomain_period.import_url', $config['import_url']);
        $container->setParameter('loevgaard_dandomain_period.dandomain_url', $config['dandomain_url']);
        $container->setParameter('loevgaard_dandomain_period.dandomain_username', $config['dandomain_username']);
        $container->setParameter('loevgaard_dandomain_period.dandomain_password', $config['dandomain_password']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
