<?php

defined('TYPO3') or die();

call_user_func(static function () {

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
