<?php

/**
 * Extension Manager/Repository config file for ext "wsm_form_spamshield".
 */
$EM_CONF['wsm_form_spamshield'] = [
    'title' => 'Form Spamshield',
    'description' => 'Adds a bot detection via JavaScript and a Validator to EXT:form, GDPR compliant without a captcha.',
    'category' => 'services',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.99.99',
            'form' => '14.0.0-14.99.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'WebsiteMensch\\FormSpamshield\\' => 'Classes'
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Andre Kraus',
    'author_email' => 'service@autodudes.de',
    'author_company' => 'AutoDudes',
    'version' => '14.0.0',
];
