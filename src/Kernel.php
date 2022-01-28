<?php

namespace Lumie\WarehouseManagerApplication;

use Lumie\WarehouseManagerApplication\Command\WarehouseManagerCommand;
use Lumie\WarehouseManagerApplication\Configuration\DataConfiguration;
use Lumie\WarehouseManagerApplication\Service\ConfigurationDataProvider;
use Lumie\WarehouseManagerApplication\Service\DataProviderInterface;
use Lumie\WarehouseManagerApplication\Service\EntityManager;
use Lumie\WarehouseManagerApplication\Service\EntityManagerInterface;
use Lumie\WarehouseManagerApplication\Service\TestService;
use Lumie\WarehouseManagerApplication\Service\WarehouseManagerService;
use Lumie\WarehouseManagerApplication\Structure\Definition\ClassMappingDefinition;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class Kernel
{
    /** @var ContainerInterface $container */
    protected $container;

    public function boot()
    {
        $this->buildContainer();

        $this->loadData();
    }

    protected function loadData()
    {
        /** @var DataProviderInterface $dataProvider */
        $dataProvider = $this->container->get(DataProviderInterface::class);
        $dataProvider->load();
    }

    protected function buildContainer()
    {
        $containerBuilder = new ContainerBuilder();

        $this->registerConfiguration($containerBuilder); 

        $containerBuilder->register(EntityManager::class, EntityManager::class)->setPublic(true)->setArgument('$mapping', ClassMappingDefinition::getClassMapping());
        $containerBuilder->setAlias(EntityManagerInterface::class, EntityManager::class)->setPublic(true);

        $containerBuilder->register(ConfigurationDataProvider::class, ConfigurationDataProvider::class)->setAutowired(true)->setPublic(true)->setArgument('$configuration', $containerBuilder->getParameter('data_config'));
        $containerBuilder->setAlias(DataProviderInterface::class, ConfigurationDataProvider::class)->setPublic(true);

        $containerBuilder->register(WarehouseManagerService::class, WarehouseManagerService::class)->setPublic(true)->setAutowired(true);
        
        $this->registerCommands($containerBuilder);
        
        $containerBuilder->compile();

        $this->container = $containerBuilder;
    }

    protected function registerCommands(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->register('warehouse_manager_command', WarehouseManagerCommand::class)->setPublic(true)->setAutowired(true);
    }


    protected function registerConfiguration(ContainerBuilder $containerBuilder)
    {
        $configuration = new DataConfiguration();

        $yamlConfig = Yaml::parse(file_get_contents(__DIR__.'/../config/data.yml'));
        $processor = new Processor();
        $processedConfiguration = $processor->processConfiguration($configuration, $yamlConfig);
        $containerBuilder->setParameter('data_config', $processedConfiguration);        
    }

    public function get($id)
    {
        if($this->container->has($id))
        {
            return $this->container->get($id);
        }
        else
        {
            return null;
        }
    }
}