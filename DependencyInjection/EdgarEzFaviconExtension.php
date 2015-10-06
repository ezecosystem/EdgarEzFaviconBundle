<?php

namespace EdgarEz\FaviconBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EdgarEzFaviconExtension extends Extension
{
    public function load( array $configs, ContainerBuilder $container )
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator( __DIR__.'/../Resources/config'));
        $loader->load('default_settings.yml');
        $loader->load('services.yml');

        $processor = new ConfigurationProcessor( $container, 'edgar_ez_favicon' );
        $processor->mapSetting( 'api_key', $config );
        $processor->mapSetting( 'master_picture', $config );
        $processor->mapSetting( 'package_dest', $config );
        $processor->mapSetting( 'favicons_view', $config );
        $processor->mapSetting( 'baseurl', $config );
        $processor->mapSetting( 'uri', $config );
    }
}
