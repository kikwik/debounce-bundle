<?php


namespace Kikwik\DebounceBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class KikwikDebounceExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $debounceService = $container->getDefinition('kikwik_debounce.service.debounce');
        $debounceService->setArgument('$apiUrl', $config['api_url']);
        $debounceService->setArgument('$apiKey', $config['api_key']);
        $debounceService->setArgument('$safeCodes', $config['safe_codes']);
    }

}