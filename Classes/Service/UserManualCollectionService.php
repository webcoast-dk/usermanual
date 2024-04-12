<?php

namespace WEBcoast\UserManual\Service;

use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\SingletonInterface;

class UserManualCollectionService implements SingletonInterface
{
    protected array $userManuals = [];
    public function __construct(protected PackageManager $packageManager)
    {
        foreach ($this->packageManager->getActivePackages() as $package) {
            if (file_exists($userManualConfigFile = $package->getPackagePath() . 'Configuration/UserManual.php')) {
                $addedManuals = require_once $userManualConfigFile;
                foreach ($addedManuals as $menu => &$configuration) {
                    if (!array_key_exists($menu, $this->userManuals)) {
                        $configuration['extension'] = $package->getValueFromComposerManifest('extra')?->{'typo3/cms'}?->{'extension-key'};
                    }
                }
                $this->userManuals = array_replace_recursive($this->userManuals, $addedManuals);
            }
        }
    }

    public function getAll(): array
    {
        return $this->userManuals;
    }
}
