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

namespace WebsiteMensch\FormSpamshield\Mvc\Validation;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use WebsiteMensch\FormSpamshield\Provider\ValidationResultProvider;

class SecureCheckValidator extends AbstractValidator
{
    /**
     * Count the number of all tests
     * @var int
     */
    protected $testCounter = 0;

    /**
     * Count the number of valid tests
     * @var int
     */
    protected $countValidTests = 0;

    /**
     * Supported options
     * @var array<string, Array<string>> $supportedOptions
     */
    protected $supportedOptions = [
        'securityLevel' => ['', 'Security Level', 'string'],
        'formTimeout' => [5, 'Form timeout, minimum amount of seconds before form can be send', 'int'],
    ];

    protected function isValid($value): void
    {
        if (!is_string($value)) {
            $this->displayError();
            return;
        }

        // $securityChecks = json_decode($value, true);
        $securityChecks = json_decode(ValidationResultProvider::getValidationResult(), true);

        // TODO check why validator is executed twice

        /**
         * Security Level default 6
         * @var int
         */
        $securityLevel = ((array_key_exists('securityLevel', $this->options)) ? intval($this->options['securityLevel']) : 6) * 10;
        // Make sure, security level have a usefull value
        $securityLevel = (($securityLevel > 100) ? 100 : $securityLevel);
        $securityLevel = (($securityLevel < 10) ? 10 : $securityLevel);

        if (!$value || !is_array($securityChecks) || !is_string($value)) {
            $this->displayError();
            return;
        }

        // Start working
        // Check if a basic set of informations is given for identify a human
        if (!$this->checkMinimumInfos($securityChecks)) {
            $this->displayError();
            return;
        }

        // Check if form is send to fast
        $formTimeout = ((array_key_exists('formTimeout', $this->options)) ? intval($this->options['formTimeout']) : 5);
        if ($securityChecks['seconds'] < $formTimeout) {
            $this->displayError();
            return;
        } else {
            $this->countValidTests++;
            $this->testCounter++;
        }

        // Check additional infos
        $this->checkAdditionalInfos($securityChecks);
        // Check mobile device
        $this->isMobile();
        // Check if all values are valid
        foreach ($securityChecks as $key => $check) {
            $this->validateCheck($key, $check);
        }

        // calculate security level: percentage of valid tests, * 100 and parsed to int to compare with security level.
        $securityCalculation = intval(
            ($this->countValidTests / $this->testCounter) * 100
        );

        // finally check if security level is given:
        if ($securityCalculation < $securityLevel) {
            $this->displayError();
        }
    }

    /**
     * Checks if a minimum set of needed infos is given.
     * If not, the sender must be a spam bot.
     * @param array<string, string> $checks
     */
    protected function checkMinimumInfos(array $checks): bool
    {
        // this minimum values must be set:
        $minimValues = ['seconds', 'displayWidth', 'displayHeight', 'formRenderedHeight', 'formRenderedWidth'];

        foreach ($minimValues as $neededCheck) {
            $this->testCounter++;
            if (!array_key_exists($neededCheck, $checks)) {
                return false;
            }
            $this->countValidTests++;
        }
        return true;
    }

    /**
     * Check which of the additional infos are given.
     * @param array<string, string> $checks
     */
    protected function checkAdditionalInfos(array $checks): bool
    {
        // needed values for this level:
        $minimValues = ['scroll', 'mousemove', 'mouseClickX', 'mouseClickY', 'keypress', 'pressedAT', 'pressedWhiteSpace'];
        foreach ($minimValues as $neededCheck) {
            $this->testCounter++;
            if (array_key_exists($neededCheck, $checks)) {
                $this->countValidTests++;
            }
        }
        return true;
    }

    /**
     * Check all of the given infos, if the value seems to be produced by a human.
     */
    protected function validateCheck(string $key, int $check): bool
    {
        $valid = false;

        // remember the test
        $this->testCounter++;

        // testing: prepared to add more complex tests for each key
        if ($key === 'seconds' && $check > 5) {
            // users need more then 5 Seconds usually
            $valid = true;
        }
        if ($key === 'displayHeight' && $check > 200) {
            // display schould be higher then 200px, but remember old iPhones in landscape
            $valid = true;
        }
        if ($key === 'displayWidth' && $check > 200) {
            // display schould be bigger then 200px, but remember old iPhones in portrait
            $valid = true;
        }
        if ($key === 'formRenderedHeight' && $check > 20) {
            // normaly, a form field or a submit button is higher then 20 px
            $valid = true;
        }
        if ($key === 'formRenderedWidth' && $check > 50) {
            // normaly, a form smaller then 20px is not usable
            $valid = true;
        }
        if ($key === 'scroll' && $check > 5) {
            // if you scroll a little, this value is much higher then 5
            $valid = true;
        }
        if ($key === 'mousemove' && $check > 2) {
            // Normal users klick in the first field to add infos there. So inmost cases, they have to move the mouse.
            // But rememer A11Y, some users cannot use a mouse.
            // So security level 10 might excludes A11Y users.
            // Mobile users are detected differently.
            $valid = true;
        }
        if ($key === 'mouseClickX' && $check > 10) {
            // Normal users minimum klick in the first field. But rememer A11Y, some users cannot use a mouse.
            // So security level 10 might excludes A11Y users.
            $valid = true;
        }
        if ($key === 'mouseClickY' && $check > 10) {
            // Normal users minimum klick in the first field. But rememer A11Y, some users cannot use a mouse.
            // So security level 10 might excludes A11Y users.
            $valid = true;
        }
        if ($key === 'keypress' && $check > 5) {
            // To insert any value, the user must press a key.
            $valid = true;
        }
        if ($key === 'pressedAT' && $check > 0) {
            // In most cases, there is a need of a mail address.
            // To insert a valid mail address, the user must insert a "@" sign.
            $valid = true;
        }
        if ($key === 'pressedWhiteSpace' && $check > 0) {
            // Maybe this is not necessary for every form, but if the user writes a message,
            // he needs whitspaces between the words. Also for names or company names.
            $valid = true;
        }

        // remember test success
        if ($valid) {
            $this->countValidTests++;
        }

        return $valid;
    }

    /**
     * Check if the user is here via a mobile device.
     * This is a additional human detection, but must not count for not-mobile-devices.
     * (because of "securityLevel 10 -> 100% valid tests.")
     * This test is added, because mobile users cannot move a mouse.
     */
    protected function isMobile(): bool
    {
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $this->testCounter++;
            $this->countValidTests++;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Print error message.
     */
    protected function displayError(): void
    {
        if ($this->result->hasErrors()) {
            return;
        }

        $customErrorMessages = ValidationResultProvider::getErrorMessages();
        if (!empty($customErrorMessages) && array_key_exists('message', $customErrorMessages[0])) {
            $this->addError(
                $customErrorMessages[0]['message'],
                1689279306
            );
            return;
        }
        $this->addError(
            $this->translateErrorMessage(
                'form.validator.securitycheck.notvalid',
                'wsmFormSpamshield'
            ),
            1623240740
        );
    }
}
