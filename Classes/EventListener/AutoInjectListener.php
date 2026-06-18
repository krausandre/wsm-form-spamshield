<?php

/***
 *
 * This file is part of the "wsm_form_spamshield" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 André Kraus <andre.kraus@website-mensch.de>, Website Mensch
 *
 ***/

declare(strict_types=1);

namespace WebsiteMensch\FormSpamshield\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;
use TYPO3\CMS\Form\Event\AfterCurrentPageIsResolvedEvent;
use WebsiteMensch\FormSpamshield\Mvc\Validation\SecureCheckValidator;

#[AsEventListener(identifier: 'wsm-form-spamshield/auto-inject')]
final class AutoInjectListener
{
    private const FIELD_ID = 'secureCheck';

    private const ELEMENT_TYPE = 'SecureCheck';

    public function __invoke(AfterCurrentPageIsResolvedEvent $event): void
    {
        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('wsm_form_spamshield');

        if (empty($config['autoInject'])) {
            return;
        }

        // never touch the backend form editor preview.
        $renderingOptions = $event->formRuntime->getFormDefinition()->getRenderingOptions();
        if (($renderingOptions['previewMode'] ?? false)
            || ApplicationType::fromRequest($event->request)->isBackend()
        ) {
            return;
        }

        // summary page doesn't render elements, so we skip it.
        $targetPage = $event->currentPage ?? $event->lastDisplayedPage;
        if ($targetPage === null || $targetPage->getType() === 'SummaryPage' || $this->hasSecureCheck($targetPage)) {
            return;
        }

        // createElement applies the prototype properties (CSS class for the JS), but the validator has to be attached at runtime.
        $element = $targetPage->createElement(self::FIELD_ID, self::ELEMENT_TYPE);
        $validator = GeneralUtility::makeInstance(SecureCheckValidator::class);
        $validator->setOptions([
            'securityLevel' => (int)($config['securityLevel'] ?? 7),
            'formTimeout' => (int)($config['formTimeout'] ?? 5),
            'strictMode' => (bool)($config['strictMode'] ?? true),
            'requireWhitespace' => (bool)($config['requireWhitespace'] ?? true),
        ]);
        $element->addValidator($validator);
    }

    private function hasSecureCheck(Page $page): bool
    {
        foreach ($page->getRenderablesRecursively() as $element) {
            if ($element instanceof FormElementInterface && $element->getType() === self::ELEMENT_TYPE) {
                return true;
            }
        }
        return false;
    }
}
