<?php

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$iconregistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconregistry->registerIcon(
    'wsm-form-spamshield',
    SvgIconProvider::class,
    ['source' => 'EXT:wsm_form_spamshield/Resources/Public/Icons/Extension.svg']
);
