<?php

declare(strict_types=1);

use phpDocumentor\Guides\DependencyInjection\GuidesExtension;
use phpDocumentor\Guides\RestructuredText\DependencyInjection\ReStructuredTextExtension;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use T3Docs\GuidesExtension\DependencyInjection\Typo3GuidesExtension;
use TYPO3\CMS\Core\Core\Environment;


return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $containerConfigurator->parameters()->set('vendor_dir', Environment::getProjectPath() . '/vendor');
//    $containerConfigurator->parameters()->set('phpdoc.rst.code_language_labels', []);
    $containerBuilder->prependExtensionConfig('re_structured_text', []);
    $containerBuilder->registerExtension(new GuidesExtension());
    $containerBuilder->registerExtension(new ReStructuredTextExtension());
    $containerBuilder->registerExtension(new Typo3GuidesExtension());
//    $containerBuilder->addCompilerPass(new ParserRulesPass());
//    $containerBuilder->addCompilerPass(new NodeRendererPass());
//    $containerBuilder->addCompilerPass(new RendererPass());
    $containerBuilder->addCompilerPass(new \WEBcoast\UserManual\DependencyInjection\CleanupPass());

//    ->set('guides.graphs.plantuml_binary', null)
//    ->set('guides.graphs.plantuml_server', null)
//    ->set('guides.graphs.renderer', null);
    $containerConfigurator->services()
        ->set(LoggerInterface::class);
//    $containerConfigurator->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides/resources/config/*.php');
//    $containerConfigurator->import(Environment::getProjectPath() . '/vendor/phpdocumentor/guides-restructured-text/resources/config/*.php');
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
