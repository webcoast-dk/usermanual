<?php

namespace WEBcoast\UserManual\Controller;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WEBcoast\UserManual\Service\UserManualCollectionService;

#[AsController]
class UserManualController
{
    public function __construct(protected ModuleTemplateFactory $moduleTemplateFactory, protected UserManualCollectionService $collectionService, protected UriBuilder $uriBuilder)
    {
    }

    public function handleRequest(ServerRequestInterface $request)
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($request);
        $selectedManual = null;
        if (count($this->collectionService->getAll()) > 0) {
            if (count($this->collectionService->getAll()) > 1) {
                $selectedManual = $request->getQueryParams()['userManual'] ?? array_key_first($this->collectionService->getAll());
                $menuRegistry = $moduleTemplate->getDocHeaderComponent()->getMenuRegistry();
                $menu = $menuRegistry->makeMenu()->setIdentifier('userManual')->setLabel($this->getLanguageService()->sL('LLL:EXT:user_manual/Resources/Private/Language/Module.xlf:menu.userManual'));
                foreach ($this->collectionService->getAll() as $identifier => $userManual) {
                    $title = $userManual['title'];
                    if (str_starts_with($title, 'LLL:')) {
                        $title = $this->getLanguageService()->sL($title);
                    }
                    $menu->addMenuItem($menu->makeMenuItem()->setTitle($title)->setActive($selectedManual === $identifier)->setHref($this->uriBuilder->buildUriFromRoute('user_user-manual', ['userManual' => $identifier])));
                }
                $menuRegistry->addMenu($menu);
            } else {
                $selectedManual = array_key_first($this->collectionService->getAll());
            }
        }

        if ($selectedManual) {
            $moduleTemplate->assign('renderedManual', 'Rendering the manual ' . $selectedManual);
        }

        return $moduleTemplate->renderResponse('UserManual/Render');
    }

    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'] ?? GeneralUtility::makeInstance(LanguageServiceFactory::class)->createFromUserPreferences($GLOBALS['BE_USER']);
    }
}
