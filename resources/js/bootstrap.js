import axios from 'axios';
import Alpine from 'alpinejs';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- Dark/Light Mode Switcher Logic as an Alpine Store ---
// We listen for Alpine to initialize, then define our global 'theme' store.
document.addEventListener('alpine:init', () => {
    Alpine.store('theme', {
        isDark: false,

        // This function runs when the store is first initialized.
        init() {
            this.isDark = this.getInitialTheme();
            this.updateHtmlClass();
        },

        // Checks localStorage first, then falls back to system preference.
        getInitialTheme() {
            if ('theme' in localStorage) {
                return localStorage.theme === 'dark';
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        },

        // The function the toggle button will call.
        toggle() {
            this.isDark = !this.isDark;
            localStorage.theme = this.isDark ? 'dark' : 'light';
            this.updateHtmlClass();
        },

        // Applies the 'dark' class to the root <html> element.
        updateHtmlClass() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });

    // Initialize the theme store as soon as Alpine is ready.
    Alpine.store('theme').init();
});

// Make Alpine globally accessible to be started in app.js.
window.Alpine = Alpine;

