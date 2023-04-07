<?php

declare(strict_types=1);

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

namespace WebsiteMensch\FormSpamshield\Domain\Model\Finishers;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Finishers\Exception\FinisherException;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Form\Domain\Runtime\FormState;

class SpamshieldFinisher extends AbstractFinisher
{
    /**
     * Executes this finisher
     * @see AbstractFinisher::execute()
     *
     * @throws FinisherException
     */
    protected function executeInternal()
    {
        $formValues = $this->finisherContext->getFormValues();
        $formRuntime = $this->finisherContext->getFormRuntime();
        foreach ($formValues as $key => $value) {
            $fieldConfig = $formRuntime->getFormDefinition()->getElementByIdentifier($key);

            if ($fieldConfig !== null && $fieldConfig->getType() === 'SecureCheck' && strlen('' . $value) > 5) {
                $humanreadableValue = '';
                if (isset($fieldConfig->getProperties()['secureCheckSuccessMessage']) && strlen('' . $fieldConfig->getProperties()['secureCheckSuccessMessage']) > 0) {
                    $humanreadableValue = $fieldConfig->getProperties()['secureCheckSuccessMessage'];
                } else {
                    /** @var LanguageService  */
                    $languageService = GeneralUtility::makeInstance(LanguageService::class);
                    $humanreadableValue = $languageService->sL('LLL:EXT:wsm_form_spamshield/Resources/Private/Language/locallang.xlf:spamshield.finisher.message');
                }
                if ($formRuntime->getFormState() instanceof FormState) {
                    $formRuntime->getFormState()->setFormValue($key, $humanreadableValue);
                }
            }
        }
        return '';
    }
}
