/**
 * ==========================================================================
 * NVHFAST - COMPOSANT POO SMOOTH SCROLL & HISTORY (PRODUCTION CORE)
 * ==========================================================================
 * Responsabilité unique : Orchestrer le défilement et la synchronisation des URLs.
 */
export class Navigation {

    constructor(onLinkClickCallback = null) {
        this.navLinks = [];
        this.observer = null;
        this.isScrolling = false;
        this.onLinkClickCallback = onLinkClickCallback; // Reçoit le signal de fermeture
    }

    init() {
        this.navLinks = document.querySelectorAll('nav ul a, .logo-studio, .cta-group a');

        if (this.navLinks.length > 0) {
            this.registerEvents();
            this.initIntersectionObserver();
            this.handleDeepLinking();
        }
    }

    registerEvents() {
        this.navLinks.forEach(link => {
            link.addEventListener('click', (e) => this.handleLinkClick(e, link));
        });

        window.addEventListener('popstate', () => this.handleDeepLinking());
    }

    handleLinkClick(e, link) {
        const sectionId = link.getAttribute('data-section');
        const targetSection = document.getElementById(sectionId);

        if (!targetSection) return;

        e.preventDefault();

        // Déclenche l'action de fermeture du menu si elle existe (ton menu burger)
        if (typeof this.onLinkClickCallback === 'function') {
            this.onLinkClickCallback();
        }

        const url = link.getAttribute('href');
        if (url && window.location.pathname !== url) {
            history.pushState(null, '', url);
        }

        this.isScrolling = true;

        // Appel natif du scroll fluide géré par le navigateur
        targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        setTimeout(() => {
            this.isScrolling = false;
            this.updateActiveLink(sectionId);
        }, 800);
    }

    initIntersectionObserver() {
        const options = {
            root: null,
            rootMargin: '-30% 0px -60% 0px',
            threshold: 0
        };

        this.observer = new IntersectionObserver((entries) => {
            if (this.isScrolling) return;

            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.id;
                    this.updateActiveLink(sectionId);
                    this.updateUrlFromSection(sectionId);
                }
            });
        }, options);

        this.navLinks.forEach(link => {
            const sectionId = link.getAttribute('data-section');
            const section = document.getElementById(sectionId);
            if (section) this.observer.observe(section);
        });
    }

    handleDeepLinking() {
        const currentPath = window.location.pathname;

        const matchingLink = Array.from(this.navLinks).find(link => {
            return link.getAttribute('href').endsWith(currentPath);
        });

        if (matchingLink) {
            const sectionId = matchingLink.getAttribute('data-section');
            const targetSection = document.getElementById(sectionId);

            if (targetSection) {
                setTimeout(() => {
                    targetSection.scrollIntoView({ behavior: 'auto', block: 'start' });
                    this.updateActiveLink(sectionId);
                }, 100);
            }
        }
    }

    updateUrlFromSection(sectionId) {
        const matchingLink = Array.from(this.navLinks).find(link => link.getAttribute('data-section') === sectionId);
        if (matchingLink) {
            const url = matchingLink.getAttribute('href');
            if (url && window.location.pathname !== url) {
                history.replaceState(null, '', url);
            }
        }
    }

    updateActiveLink(sectionId) {
        this.navLinks.forEach(link => {
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
}
