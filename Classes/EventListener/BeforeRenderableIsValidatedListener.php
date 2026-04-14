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

namespace WebsiteMensch\FormSpamshield\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Form\Domain\Model\FormElements\GenericFormElement;
use TYPO3\CMS\Form\Event\BeforeRenderableIsValidatedEvent;
use WebsiteMensch\FormSpamshield\Domain\Model\FormElements\SecureCheckElement;
use WebsiteMensch\FormSpamshield\Provider\ValidationResultProvider;

#[AsEventListener(identifier: 'wsm-form-spamshield/before-renderable-is-validated')]
final class BeforeRenderableIsValidatedListener
{
    public function __invoke(BeforeRenderableIsValidatedEvent $event): void
    {
        $renderable = $event->renderable;

        if ($renderable->getType() !== 'SecureCheck') {
            return;
        }

        $elementValue = $event->value;

        // Remember the value for the validator
        ValidationResultProvider::rememberValidation('' . $elementValue);

        // Extract and remember custom error messages
        $errorMessages = [0 => []];
        if (
            is_array($renderable->getProperties())
            && array_key_exists('validationErrorMessages', $renderable->getProperties())
            && is_array($renderable->getProperties()['validationErrorMessages'])
        ) {
            $errorMessages = $renderable->getProperties()['validationErrorMessages'];
            if (
                array_key_exists(0, $errorMessages)
                && array_key_exists('message', $errorMessages[0])
                && strpos($errorMessages[0]['message'], 'LLL:EXT:') !== false
            ) {
                $errorMessages[0]['message'] = LocalizationUtility::translate($errorMessages[0]['message']) ?: 'Translation error';
            }
        }
        ValidationResultProvider::rememberErrorMessages($errorMessages);

        // Set the success message as element value
        if (
            $renderable instanceof SecureCheckElement
            && is_array($renderable->getProperties())
            && array_key_exists('secureCheckSuccessMessage', $renderable->getProperties())
            && strlen('' . $renderable->getProperties()['secureCheckSuccessMessage']) > 0
        ) {
            $elementValue = $renderable->getProperties()['secureCheckSuccessMessage'];
            if (strpos($elementValue, 'LLL:EXT:') !== false) {
                $elementValue = LocalizationUtility::translate($elementValue) ?: 'Translation error';
            }
        } else {
            $elementValue = LocalizationUtility::translate(
                'LLL:EXT:wsm_form_spamshield/Resources/Private/Language/locallang.xlf:spamshield.finisher.message',
                'wsm_form_spamshield'
            );
        }

        $event->value = $elementValue;
    }
}
