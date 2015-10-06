<?php

namespace EdgarEz\FaviconBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('edgar_ez_favicon');

        // $systemNode will then be the root of siteaccess aware settings.
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->scalarNode('api_key')->isRequired()->end()
            ->scalarNode('master_picture')->isRequired()->end()
            ->scalarNode('package_dest')->isRequired()->end()
            ->scalarNode('favicons_view')->isRequired()->end()
            ->scalarNode('baseurl')->end()
            ->scalarNode('uri')->end();

        return $treeBuilder;
    }
}
