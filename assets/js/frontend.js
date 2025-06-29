/**
 * Elegant Popups - JavaScript Frontend
 * Gestion des pop-ups avec glassmorphism design
 */

const DEBUG = window.elegantPopupsData?.debug || false;

class ElegantPopups {
    constructor() {
        this.options = window.elegantPopupsData?.options || {};
        this.isExitIntentTriggered = false;
        this.isWelcomeShown = false;
        this.activePopup = null;
        
        // Vérification du stockage local pour le pop-up d'accueil
        this.welcomeShownBefore = localStorage.getItem('elegant_welcome_shown') === 'true';
        
        // Debug
        if (DEBUG) console.log('ElegantPopups initialized', this.options);
        
        this.init();
    }
    
    init() {
        // Attendre que le DOM soit chargé
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupPopups());
        } else {
            this.setupPopups();
        }
    }
    
    setupPopups() {
        if (DEBUG) console.log('Setting up popups...');
        this.setupWelcomePopup();
        this.setupExitPopup();
        this.setupEventListeners();
        this.setupAccessibility();
    }
    
    setupWelcomePopup() {
        if (!this.options.welcome_popup?.enabled) {
            if (DEBUG) console.log('Welcome popup disabled');
            return;
        }
        
        const popup = document.getElementById('elegant-welcome-popup');
        if (!popup) {
            if (DEBUG) console.log('Welcome popup element not found');
            return;
        }
        
        if (DEBUG) console.log('Welcome popup found, checking conditions...');
        
        // Vérifier si on doit afficher une seule fois
        if (this.options.welcome_popup.show_once && this.welcomeShownBefore) {
            if (DEBUG) console.log('Welcome popup already shown before');
            return;
        }
        
        const delay = parseInt(this.options.welcome_popup.delay) || 3000;
        if (DEBUG) console.log('Setting welcome popup delay:', delay);
        
        setTimeout(() => {
            if (!this.isWelcomeShown && !this.activePopup) {
                if (DEBUG) console.log('Showing welcome popup');
                this.showPopup('welcome');
            }
        }, delay);
    }
    
    setupExitPopup() {
        if (!this.options.exit_popup?.enabled) {
            if (DEBUG) console.log('Exit popup disabled');
            return;
        }
        
        if (DEBUG) console.log('Setting up exit popup listeners...');
        
        // Détection desktop : souris qui sort du viewport
        document.addEventListener('mouseleave', (e) => {
            if (e.clientY <= 0 && !this.isExitIntentTriggered && !this.activePopup) {
                if (DEBUG) console.log('Exit intent detected (mouse)');
                this.showPopup('exit');
                this.isExitIntentTriggered = true;
            }
        });
        
        // Détection mobile : scroll rapide vers le haut
        let lastScrollTop = 0;
        let scrollStartTime = 0;
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const currentTime = Date.now();
            
            if (scrollTop === 0 && lastScrollTop > 50) {
                // Scroll rapide vers le haut sur mobile
                if (currentTime - scrollStartTime < 500 && !this.isExitIntentTriggered && !this.activePopup) {
                    if (DEBUG) console.log('Exit intent detected (scroll)');
                    this.showPopup('exit');
                    this.isExitIntentTriggered = true;
                }
            }
            
            if (scrollTop > lastScrollTop) {
                scrollStartTime = currentTime;
            }
            
            lastScrollTop = scrollTop;
        }, { passive: true });
        
        // Détection mobile : geste de retour/fermeture
        let touchStartY = 0;
        document.addEventListener('touchstart', (e) => {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        document.addEventListener('touchmove', (e) => {
            const touchY = e.touches[0].clientY;
            const diff = touchStartY - touchY;
            
            // Swipe vers le haut rapide depuis le haut de l'écran
            if (touchStartY < 50 && diff > 100 && !this.isExitIntentTriggered && !this.activePopup) {
                if (DEBUG) console.log('Exit intent detected (touch)');
                this.showPopup('exit');
                this.isExitIntentTriggered = true;
            }
        }, { passive: true });
    }
    
    setupEventListeners() {
        // Fermeture des pop-ups
        document.addEventListener('click', (e) => {
            if (e.target.matches('.elegant-popup-close') || 
                e.target.matches('.elegant-popup-close span')) {
                if (DEBUG) console.log('Close button clicked');
                this.closeActivePopup();
            }
            
            // Clic sur l'overlay
            if (e.target.matches('.elegant-popups-overlay')) {
                if (DEBUG) console.log('Overlay clicked');
                this.closeActivePopup();
            }
        });
        
        // Gestion du clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activePopup) {
                if (DEBUG) console.log('Escape key pressed');
                this.closeActivePopup();
            }
            
            // Navigation au clavier dans le pop-up
            if (this.activePopup && e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }
    
    setupAccessibility() {
        // S'assurer que les pop-ups sont accessibles
        const popups = document.querySelectorAll('.elegant-popup');
        popups.forEach(popup => {
            popup.setAttribute('tabindex', '-1');
        });
    }
    
    showPopup(type) {
        if (this.activePopup) {
            if (DEBUG) console.log('Another popup is already active');
            return;
        }
        
        const popupId = type === 'welcome' ? 'elegant-welcome-popup' : 'elegant-exit-popup';
        const popup = document.getElementById(popupId);
        const overlay = document.getElementById('elegant-popups-overlay');
        
        if (!popup || !overlay) {
            if (DEBUG) console.log('Popup or overlay not found:', popupId);
            return;
        }
        
        if (DEBUG) console.log('Showing popup:', type);
        this.activePopup = popup;
        
        // Afficher l'overlay
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        
        // Afficher le pop-up avec un petit délai pour l'animation
        setTimeout(() => {
            popup.classList.add('active');
            popup.setAttribute('aria-hidden', 'false');
            
            // Focus sur le contenu pour l'accessibilité
            const content = popup.querySelector('.elegant-popup-content');
            if (content) {
                content.focus();
            }
        }, 50);
        
        // Marquer le pop-up d'accueil comme affiché
        if (type === 'welcome') {
            this.isWelcomeShown = true;
            if (this.options.welcome_popup.show_once) {
                localStorage.setItem('elegant_welcome_shown', 'true');
            }
        }
        
        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';
        
        // Déclencher un événement personnalisé
        window.dispatchEvent(new CustomEvent('elegantPopupShown', {
            detail: { type: type, popup: popup }
        }));
    }
    
    closeActivePopup() {
        if (!this.activePopup) return;
        
        if (DEBUG) console.log('Closing active popup');
        const overlay = document.getElementById('elegant-popups-overlay');
        
        // Masquer le pop-up
        this.activePopup.classList.remove('active');
        this.activePopup.setAttribute('aria-hidden', 'true');
        
        // Masquer l'overlay
        if (overlay) {
            overlay.classList.remove('active');
            overlay.setAttribute('aria-hidden', 'true');
        }
        
        // Restaurer le scroll
        document.body.style.overflow = '';
        
        // Déclencher un événement personnalisé
        window.dispatchEvent(new CustomEvent('elegantPopupClosed', {
            detail: { popup: this.activePopup }
        }));
        
        this.activePopup = null;
    }
    
    handleTabNavigation(e) {
        if (!this.activePopup) return;
        
        const focusableElements = this.activePopup.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            // Shift + Tab
            if (document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            }
        } else {
            // Tab
            if (document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }
    
    // Méthodes publiques pour l'API
    forceShowPopup(type) {
        if (type === 'welcome') {
            this.isWelcomeShown = false;
        } else if (type === 'exit') {
            this.isExitIntentTriggered = false;
        }
        this.showPopup(type);
    }
    
    closeAllPopups() {
        this.closeActivePopup();
    }
    
    resetWelcomePopup() {
        localStorage.removeItem('elegant_welcome_shown');
        this.welcomeShownBefore = false;
        this.isWelcomeShown = false;
    }
}

// Initialisation
let elegantPopupsInstance;

// Attendre que le DOM et les scripts soient chargés
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePopups);
} else {
    initializePopups();
}

function initializePopups() {
    // Attendre un peu pour s'assurer que elegantPopupsData est disponible
    setTimeout(() => {
        if (typeof elegantPopupsData !== 'undefined') {
            elegantPopupsInstance = new ElegantPopups();
            
            // Exposer l'instance pour l'utilisation externe
            window.ElegantPopups = elegantPopupsInstance;
        } else {
            if (DEBUG) console.log('elegantPopupsData not found, retrying...');
            // Réessayer après un délai
            setTimeout(initializePopups, 500);
        }
    }, 100);
}

// Support pour les anciens navigateurs
if (!window.CustomEvent) {
    window.CustomEvent = function(event, params) {
        params = params || { bubbles: false, cancelable: false, detail: null };
        const evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    };
}