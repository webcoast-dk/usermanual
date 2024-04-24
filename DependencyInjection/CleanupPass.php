<?php

declare(strict_types=1);


namespace WEBcoast\UserManual\DependencyInjection;


use phpDocumentor\Guides\Settings\SettingsManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CleanupPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition(SettingsManager::class)->removeMethodCall('setProjectSettings');
    }
}
