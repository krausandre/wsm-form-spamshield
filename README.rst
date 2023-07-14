=================
Form Spamshield
=================

Adds a bot detection via JavaScript and a Validator to EXT:form, GDPR compliant without a captcha.

How it works
============

This shield works with two components:

The first component is a JavaScript, which checks the browser and checks if there are signs that
the current user is a human. Simple things like browser width, whether the user pressed a key, whether the user moved the mouse, and so on.
No personal data. But JavaScript must be enabled otherwise, the spam shield will detect the user as a bot.

The second component is a validator, which checks the result of the JavaScript.
You can configure a security level from 1 to 10 in your form field definition, which the validator respects.
1 means 10% of the security checks have to be successful, and 10 means 100% have to be successful.

Please remember accessibility, that there are some people e.g. who can not use a mouse. So setting the security level to 9 or 10 might not be accessible for some people.

There is the option to set a form timeout. This is a value in seconds, which defines a timeout before the form submission is accepted. E.g. robots are very fast, so a human 
needs more time to fill out a form. You can set a custom value based on what you think is the minimum number of seconds humans need to fill out the form. (Remember that 
browser autofill can be very fast.)

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
                    formTimeout: 10
        properties:
            secureCheckSuccessMessage: 'Validation passed'
            validationErrorMessages:
                -
                    code: 1221559976
                    message: 'Sorry, the security check identified you as a robot. To pass the security check, you must perform more actions on this page that are typical for a human visitor. And JavaScript must be enabled.'


You can also add the security check field with a validator to your formdefinition via the form editor. This could be helpful in some cases.
