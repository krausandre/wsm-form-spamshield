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

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        trim('
            ########################
            #### FORM FRAMEWORK ####
            ########################

            # Register custom EXT:form configuration
            module.tx_form {
                settings {
                    yamlConfigurations {
                        1678469420 = EXT:wsm_form_spamshield/Configuration/Yaml/EditorBaseSetup.yaml
                    }
                }
                view {
                    partialRootPaths {
                        1678469420 = EXT:wsm_form_spamshield/Resources/Private/Frontend/Partials/Forms/
                    }
                }
            }
            plugin.tx_form {
                settings {
                    yamlConfigurations {
                        1678469420 = EXT:wsm_form_spamshield/Configuration/Yaml/BaseSetup.yaml
                    }
                }
                view {
                    partialRootPaths {
                        1678469420 = EXT:wsm_form_spamshield/Resources/Private/Frontend/Partials/Forms/
                    }
                }
            }
        ')
    );
});
