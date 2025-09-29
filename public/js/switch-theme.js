(function($) {
    "use strict";

    // Apply theme on page load
    function applyTheme() {
        let theme = document.getElementById("theme-asset");
        if (theme) {
            let savedTheme = Cookies.get('theme');
            if (savedTheme) {
                theme.setAttribute('data-bs-theme', savedTheme);
            }
        }
    }

    // Apply theme when page loads
    document.addEventListener('DOMContentLoaded', applyTheme);

    // Theme switching function
    function handleThemeSwitch(button) {
        let theme = document.getElementById("theme-asset");
        var currentTheme = theme.getAttribute("data-bs-theme");
        let dark = 'dark';
        let light = 'light';

        // Fallback text if variables are not defined
        let darkModeText = typeof darkMode !== 'undefined' ? darkMode : 'Dark Mode';
        let lightModeText = typeof lightMode !== 'undefined' ? lightMode : 'Light Mode';

        if (currentTheme == 'dark') {
            theme.setAttribute('data-bs-theme', light);
            button.innerHTML = '<i class="bi-moon-stars me-2"></i> ' + darkModeText;
            Cookies.remove('theme');
            Cookies.set('theme', light, { expires: 365 });
        } else {
            theme.setAttribute('data-bs-theme', dark);
            button.innerHTML = '<i class="bi-sun me-2"></i> ' + lightModeText;
            Cookies.remove('theme');
            Cookies.set('theme', dark, { expires: 365 });
        }
    }

    // Handle all theme switching buttons (there might be multiple with same ID)
    let btnSwitchThemes = document.querySelectorAll('#switchTheme');

    btnSwitchThemes.forEach(button => {
        button.addEventListener('click', event => {
            handleThemeSwitch(button);
        });
    });
})(jQuery)