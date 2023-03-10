<?php

/**
 * Extension Manager/Repository config file for ext "wsm_forms_spamshield".
 */
$EM_CONF['wsm_forms_spamshield'] = [
    'title' => 'Forms Spamshield',
    'description' => 'Adds a bot detection via JavaScript and a Validator to EXT:form, GDPR compliant without a captcha.',
    'category' => 'services',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
            'form' => '10.4.0-11.5.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'WebsiteMensch\\FormsSpamshield\\' => 'Classes'
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Andre Kraus',
    'author_email' => 'info@website-mensch.de',
    'author_company' => 'Website Mensch',
    'version' => '1.0.0',
];
