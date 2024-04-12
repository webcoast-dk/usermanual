<?php

declare(strict_types=1);

return [
    'user_user-manual' => [
        'parent' => 'user',
        'access' => 'user',
        'path' => 'module/user-manual',
        'iconIdentifier' => 'ext-user-manual',
        'labels' => 'LLL:EXT:user_manual/Resources/Private/Language/Module.xlf',
        'routes' => [
            '_default' => [
                'target' => \WEBcoast\UserManual\Controller\UserManualController::class . '::handleRequest'
            ]
        ]
    ]
];
