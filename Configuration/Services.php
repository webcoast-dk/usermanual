<?php

declare(strict_types=1);

use phpDocumentor\Guides\NodeRenderers\DelegatingNodeRenderer;
use phpDocumentor\Guides\NodeRenderers\InMemoryNodeRendererFactory;
use phpDocumentor\Guides\NodeRenderers\TemplateNodeRenderer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use TYPO3\CMS\Core\Core\Environment;

return static function (ContainerConfigurator $container): void {
    $container->parameters()->set('vendor_dir', Environment::getProjectPath() . '/vendor');
//    ->set('guides.graphs.plantuml_binary', null)
//    ->set('guides.graphs.plantuml_server', null)
//    ->set('guides.graphs.renderer', null);
    $container->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides/resources/config/*.php');
    $container->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides-restructured-text/resources/config/*.php');
//    $container->services()
//        ->set(InMemoryNodeRendererFactory::class)
//        ->set(DelegatingNodeRenderer::class)
//        ->args([
//            new Reference(InMemoryNodeRendererFactory::class),
//
//        ])
//        ->tag('phpdoc.guides.output_node_renderer', ['format' => 'html']);
//    $container->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides-cli/resources/config/*.php');
//    $container->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides-graphs/resources/config/*.php');
//    $container->import(Environment::getProjectPath() . '/vendor/t3docs/typo3-guides-extension/resources/config/*.php');
//    $container->import(Environment::getProjectPath() . '/vendor/t3docs/typo3-docs-theme/resources/config/*.php');
//    $container->services()
//        ->set(\T3Docs\Typo3DocsTheme\Settings\Typo3DocsThemeSettings::class)
//    ->alias('T3Docs\\Typo3DocsTheme\\Settings\\Typo3DocsInputSettings')
};
