=================
Form Spamshield
=================

Adds a bot detection via JavaScript and a Validator to EXT:form, GDPR compliant without a captcha.

How it works
============

This shield works with two components:

The first component is a JavaScript, which checks the browser and checks if there are signs that
the current user is a human. Simple things like browser width, does the user pressed a key, does the user moved the mouse and so on.
No personal data. But JavaScript must be enabled, elsewhere the spamshild will detect the user as a bot.

The second component is a validator, which checks the result of the JavaScript.
You can configure a security level from 1 to 10 in your form field definition, which the validator respects.
1 means, 10% of the security checks have to be successful, 10 means 100% have to be successful.

Please remember accessibility, that there are some people e.g. which can not use a mouse. So set the security level to 10 might be not accessible for some people.

How to install
==============

You can install this extension via composer:

.. code-block:: bash

   composer req wsm/form-spamshield


You can also install this extension via TER.

After installation flush TYPO3 and PHP caches.

How to use
==========

Add the security check field with validator to your formdefinition like this:

.. code-block:: yaml
    -
        type: SecureCheck
        identifier: securitycheck
        label: 'Security check against robots'
        validators:
            -
                identifier: SpamSecurityCheck
                options:
                    securityLevel: 5
        properties:
            validationErrorMessages:
                -
                    code: 1678470449
                    message: 'Sorry, the security check identified you as a robot. To pass the security check, you must perform more actions on this page that are typical for a human visitor. And JavaScript must be enabled.'
