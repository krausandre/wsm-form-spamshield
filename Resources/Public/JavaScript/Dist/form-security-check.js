(function() {

    let securityTests = {};
    let form = document.querySelector('form');

    /* Count seconds, stop at 90 seconds */
    let seconds = 0;
    let secondsIntervall = setInterval(incrementSeconds, 1000);

    function incrementSeconds() {
        seconds += 1;
        securityTests['seconds'] = seconds;
        if (seconds === 2) {
            setDefaultValues();
        }
        setValue();
        // stop seconds counting at 90 seconds
        if (seconds >= 90) {
            clearInterval(secondsIntervall);
        }
    }

    let securityFieldList = Array.from(document.querySelectorAll('form input.security-check-input'));

    /** set default values after 2 seconds. */
    function setDefaultValues() {
        /* get some default values */
        securityTests['displayWidth'] = screen.width;
        securityTests['displayHeight'] = screen.height;
        securityTests['webdriver'] = navigator.webdriver ? 1 : 0;
    }

    /* Scroll */
    document.addEventListener('scroll', (event) => {
        securityTests['scroll'] = ((securityTests['scroll'] !== undefined) ? securityTests['scroll'] + 1 : 1);
        setValue();
    });

    /* MouseMove */
    document.addEventListener('mousemove', (event) => {
        securityTests['mousemove'] = ((securityTests['mousemove'] !== undefined) ? securityTests['mousemove'] + 1 : 1);
        setValue();
    });

    /* MouseClick */
    document.addEventListener('click', (event) => {
        securityTests['mouseClickX'] = event.screenX;
        securityTests['mouseClickY'] = event.screenY;
        setValue();
    });

    /* Any key press - using keydown for better mobile compatibility */
    document.addEventListener('keydown', (event) => {
        if (event.key === "@") securityTests['pressedAT'] = 1;
        if (event.key === " " || event.code === "Space") securityTests['pressedWhiteSpace'] = 1;
        securityTests['keypress'] = ((securityTests['keypress'] !== undefined) ? securityTests['keypress'] + 1 : 1);
        setValue();
    });

    /* Touch events - detect touch device interaction */
    document.addEventListener('touchstart', (event) => {
        securityTests['touchEvents'] = ((securityTests['touchEvents'] !== undefined) ? securityTests['touchEvents'] + 1 : 1);
        setValue();
    });

    document.addEventListener('touchmove', (event) => {
        securityTests['touchEvents'] = ((securityTests['touchEvents'] !== undefined) ? securityTests['touchEvents'] + 1 : 1);
        setValue();
    });

    /* Sets security values to the hidden field. */
    function setValue() {
        if (Array.isArray(securityFieldList)) {
            securityFieldList.forEach(function (securityField, index, arr) {
                let form = securityField.closest('form');
                securityTests['formRenderedHeight'] = form.offsetHeight;
                securityTests['formRenderedWidth'] = form.offsetWidth;
                securityField.value = JSON.stringify(securityTests);
            });
        }
    }

})();
