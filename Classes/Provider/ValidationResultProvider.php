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
    public static function rememberValidation(string $value): void
    {
        $_SESSION['wsm_form_spamshield'][1680905109]['securityCheckResult'] = $value;
    }

    public static function getValidationResult(): string
    {
        if (!isset($_SESSION['wsm_form_spamshield'][1680905109]['securityCheckResult'])) {
            return '';
        }
        return $_SESSION['wsm_form_spamshield'][1680905109]['securityCheckResult'];
    }
}
