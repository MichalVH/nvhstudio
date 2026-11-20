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
        this.onLinkClickCallback = onLinkClickCallback;
    }

    init() {
        // Cible automatiquement TOUS les liens possédant l'attribut data-section
        this.navLinks = document.querySelectorAll('a[data-section]');

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

        // Si la section n'existe pas sur la page courante (ex: depuis /nos-offres ou /mentions-legales),
        // on laisse le navigateur rediriger normalement vers l'accueil.
        if (!targetSection) return;

        e.preventDefault();

        // Ferme le menu burger mobile s'il est ouvert
        if (typeof this.onLinkClickCallback === 'function') {
            this.onLinkClickCallback();
        }

        const url = link.getAttribute('href');
        if (url && window.location.pathname !== url) {
            history.pushState(null, '', url);
        }

        this.isScrolling = true;

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

        // Évite d'observer plusieurs fois la même section s'il y a plusieurs boutons vers #contact
        const observedSections = new Set();

        this.navLinks.forEach(link => {
            const sectionId = link.getAttribute('data-section');
            const section = document.getElementById(sectionId);
            if (section && !observedSections.has(section)) {
                observedSections.add(section);
                this.observer.observe(section);
            }
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
