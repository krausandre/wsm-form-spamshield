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

namespace WebsiteMensch\FormSpamshield\Hooks;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use WebsiteMensch\FormSpamshield\Mvc\Validation\SecureCheckValidator;

class AutoInjectHook
{
    private const FIELD_ID = 'secureCheck';

    private const ELEMENT_TYPE = 'SecureCheck';

    public function afterInitializeCurrentPage(
        FormRuntime $formRuntime,
        ?Page       $currentPage,
        ?Page       $previousPage,
        array       $args
    ): ?Page
    {
        $config = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('wsm_form_spamshield');

        if (empty($config['autoInject'])) {
            return $currentPage;
        }

        // never touch the backend form editor preview.
        $renderingOptions = $formRuntime->getFormDefinition()->getRenderingOptions();
        if (($renderingOptions['previewMode'] ?? false)
            || ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()
        ) {
            return $currentPage;
        }

        // summary page doesn't render elements, so we skip it.
        $targetPage = $currentPage ?? $previousPage;
        if ($targetPage === null || $targetPage->getType() === 'SummaryPage' || $this->hasSecureCheck($targetPage)) {
            return $currentPage;
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

        return $currentPage;
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
