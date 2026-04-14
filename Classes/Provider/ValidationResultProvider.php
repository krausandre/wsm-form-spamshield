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

namespace WebsiteMensch\FormSpamshield\Provider;

class ValidationResultProvider
{
    private static string $validationResult = '';
    private static array $errorMessages = [];

    public static function rememberValidation(string $value): void
    {
        self::$validationResult = $value;
    }

    public static function getValidationResult(): string
    {
        return self::$validationResult;
    }

    public static function rememberErrorMessages(array $value): void
    {
        self::$errorMessages = $value;
    }

    public static function getErrorMessages(): array
    {
        if (empty(self::$errorMessages)) {
            return [''];
        }
        return self::$errorMessages;
    }
}
