<?php

defined('TYPO3') or die();

call_user_func(static function () {
    $iconregistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconregistry->registerIcon(
        'wsm-form-spamshield',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:wsm_form_spamshield/Resources/Public/Icons/Extension.svg']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterSubmit'][1680904711] = \WebsiteMensch\FormSpamshield\Hooks\AfterSubmitHook::class;
});
