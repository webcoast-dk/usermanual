<?php

declare(strict_types=1);

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
    ->registerIcon('ext-user-manual', \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, ['source' => 'EXT:user_manual/Resources/Public/Icon/Extension.svg']);
