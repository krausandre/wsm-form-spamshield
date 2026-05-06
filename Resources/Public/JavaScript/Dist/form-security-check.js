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

    /* Input events - reliable on mobile virtual keyboards and IME composition,
       where keydown often reports key="Unidentified" / keyCode=229 and never sees the space. */
    document.addEventListener('input', (event) => {
        if (typeof event.data === 'string') {
            if (event.data.indexOf(' ') !== -1) securityTests['pressedWhiteSpace'] = 1;
            if (event.data.indexOf('@') !== -1) securityTests['pressedAT'] = 1;
        } else if (event.target && typeof event.target.value === 'string') {
            /* Fallback for composition / autocorrect where event.data is null */
            if (/\s/.test(event.target.value)) securityTests['pressedWhiteSpace'] = 1;
            if (event.target.value.indexOf('@') !== -1) securityTests['pressedAT'] = 1;
        }
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
