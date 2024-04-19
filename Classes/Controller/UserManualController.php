<?php

namespace WEBcoast\UserManual\Controller;

use Flyfinder\Finder;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Tactician\CommandBus;
use phpDocumentor\Guides\Compiler\CompilerContext;
use phpDocumentor\Guides\Handlers\CompileDocumentsCommand;
use phpDocumentor\Guides\Handlers\ParseDirectoryCommand;
use phpDocumentor\Guides\Handlers\RenderCommand;
use phpDocumentor\Guides\Nodes\ProjectNode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use WEBcoast\UserManual\Service\UserManualCollectionService;

#[AsController]
class UserManualController
{
    public function __construct(protected ModuleTemplateFactory $moduleTemplateFactory, protected UserManualCollectionService $collectionService, protected UriBuilder $uriBuilder, protected CommandBus $commandBus)
    {
    }

    public function handleRequest(ServerRequestInterface $request): ResponseInterface
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

        $manualConfiguration = $this->collectionService->getAll()[$selectedManual] ?? null;
        if ($manualConfiguration) {
            $moduleTemplate->assign('renderedManual', $this->renderManual($manualConfiguration));
        }

        return $moduleTemplate->renderResponse('UserManual/Render');
    }

    protected function renderManual(array $manualConfiguration): string
    {
        $title = $manualConfiguration['title'];
        if (str_starts_with($title, 'LLL:')) {
            $title = $this->getLanguageService()->sL($title);
        }
        $projectNode = new ProjectNode($title);
        $sourceFileSystem = new Filesystem(new Local(GeneralUtility::getFileAbsFileName('EXT:' . $manualConfiguration['extension'] . '/' . ltrim($manualConfiguration['path'], '/'))));
        $sourceFileSystem->addPlugin(new Finder());
        $temporaryDirName = GeneralUtility::tempnam('user_manual_');
        unlink($temporaryDirName);
        $destinationFileSystem = new Filesystem(new Local($temporaryDirName));
        $documents = $this->commandBus->handle(
            new ParseDirectoryCommand($sourceFileSystem, '', 'rst', $projectNode)
        );
        $documents = $this->commandBus->handle(
            new CompileDocumentsCommand($documents, new CompilerContext($projectNode))
        );
        $this->commandBus->handle(
            new RenderCommand('html', $documents, $sourceFileSystem, $destinationFileSystem, $projectNode)
        );

        return 'Rendering the manual ' . $title;
    }

    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'] ?? GeneralUtility::makeInstance(LanguageServiceFactory::class)->createFromUserPreferences($GLOBALS['BE_USER']);
    }
}
