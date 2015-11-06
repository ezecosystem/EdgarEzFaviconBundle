<?php

namespace EdgarEz\FaviconBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    /**
     * Initialize bundle configuration
     *
     * @return TreeBuilder
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
            ->arrayNode('favicon_design')
                ->children()
                    ->arrayNode('desktop_browser')
                        ->children()
                        ->end()
                    ->end()
                    ->arrayNode('ios')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->integerNode('margin')->end()
                            ->scalarNode('background_color')->end()
                        ->end()
                    ->end()
                    ->arrayNode('windows')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->scalarNode('background_color')->end()
                        ->end()
                    ->end()
                    ->arrayNode('firefox_app')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->booleanNode('keep_picture_in_circle')->end()
                            ->integerNode('circle_inner_margin')->end()
                            ->scalarNode('background_color')->end()
                            ->arrayNode('manifest')
                                ->children()
                                    ->scalarNode('app_name')->end()
                                    ->scalarNode('app_description')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('android_chrome')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->scalarNode('theme_color')->end()
                            ->arrayNode('manifest')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('display')->end()
                                    ->scalarNode('orientation')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('safari_pinned_tab')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->integerNode('threshold')->end()
                            ->scalarNode('theme_color')->end()
                        ->end()
                    ->end()
                    ->arrayNode('coast')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->scalarNode('background_color')->end()
                            ->integerNode('margin')->end()
                        ->end()
                    ->end()
                    ->arrayNode('open_graph')
                        ->children()
                            ->scalarNode('picture_aspect')->end()
                            ->scalarNode('background_color')->end()
                            ->integerNode('margin')->end()
                            ->scalarNode('ratio')->end()
                        ->end()
                    ->end()
                    ->arrayNode('yandex_browser')
                        ->children()
                            ->scalarNode('background_color')->end()
                            ->arrayNode('manifest')
                                ->children()
                                    ->scalarNode('show_title')->end()
                                    ->scalarNode('version')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->scalarNode('baseurl')->end()
            ->scalarNode('uri')->end()
            ->booleanNode('versioning')->end();

        return $treeBuilder;
    }
}

