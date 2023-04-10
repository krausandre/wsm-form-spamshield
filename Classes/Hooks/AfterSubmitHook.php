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

namespace WebsiteMensch\FormSpamshield\Hooks;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;
use WebsiteMensch\FormSpamshield\Domain\Model\FormElements\SecureCheckElement;
use WebsiteMensch\FormSpamshield\Provider\ValidationResultProvider;

class AfterSubmitHook
{
    /**
     * This hook is invoked by the FormRuntime for each form element
     * **after** a form page was submitted but **before** values are
     * property-mapped, validated and pushed within the FormRuntime's `FormState`.
     *
     * @param mixed $elementValue submitted value of the element *before post processing*
     * @param array $requestArguments submitted raw request values
     * @return mixed
     * @see FormRuntime::mapAndValidate()
     */
    public function afterSubmit(FormRuntime $formRuntime, RenderableInterface $renderable, $elementValue, array $requestArguments = [])
    {
        if ($renderable->getType() === 'SecureCheck') {
            // remember the value for the validator
            ValidationResultProvider::rememberValidation($elementValue);
            if (
                $renderable instanceof SecureCheckElement
                && isset($renderable->getProperties()['secureCheckSuccessMessage'])
                && strlen('' . $renderable->getProperties()['secureCheckSuccessMessage']) > 0
            ) {
                $elementValue = $renderable->getProperties()['secureCheckSuccessMessage'];
            } else {
                // /** @var LanguageService */
                // $languageService = GeneralUtility::makeInstance(LanguageService::class);
                // $elementValue = $languageService->sL('LLL:EXT:wsm_form_spamshield/Resources/Private/Language/locallang.xlf:spamshield.finisher.message');
                $elementValue = LocalizationUtility::translate('spamshield.finisher.message', 'wsm_form_spamshield');
            }
        }
        return $elementValue;
    }
}
