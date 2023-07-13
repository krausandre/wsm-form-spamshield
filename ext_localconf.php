<?php

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use WebsiteMensch\FormSpamshield\Hooks\AfterSubmitHook;

defined('TYPO3') or die();

call_user_func(static function () {
    $iconregistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconregistry->registerIcon(
        'wsm-form-spamshield',
        SvgIconProvider::class,
        ['source' => 'EXT:wsm_form_spamshield/Resources/Public/Icons/Extension.svg']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterSubmit'][1680904711] = AfterSubmitHook::class;
});
