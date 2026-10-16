/**
 * ==========================================================================
 * NVHFAST - COMPOSANT POO MENU BURGER & DROPDOWN (VERSION MODERNE BLINDÉE)
 * ==========================================================================
 */
export class Menu {

    constructor() {
        this.burgerBtn = null;
        this.navMenu = null;
        this.isOpen = false;

        // 🌟 LA BRIQUE MODERNE : On crée l'écouteur de média query JavaScript
        this.mediaQueryMobile = window.matchMedia('(max-width: 999px)');
    }

    init() {
        this.burgerBtn = document.getElementById('burger-btn');
        this.navMenu = document.querySelector('.menu-drawer');

        if (this.burgerBtn && this.navMenu) {
            this.registerEvents();
        }
    }

    registerEvents() {
        // 1. Écouteur pour le bouton burger mobile (inchangé)
        this.burgerBtn.addEventListener('click', () => this.toggleMenu());

        // 2. Écouteur universel au clic pour le sous-menu (Desktop, Surface, Mobile)
        const dropdownTrigger = document.querySelector('.dropdown-trigger');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        if (dropdownTrigger && dropdownMenu) {
            dropdownTrigger.addEventListener('click', (e) => {
                e.preventDefault(); // Bloque le comportement natif du lien '#'
                dropdownMenu.classList.toggle('open'); // Alterne l'affichage du sous-menu
                dropdownTrigger.classList.toggle('arrow-rotated'); // Fait pivoter la flèche
            });

            // SÉCURITÉ ERGONOMIQUE GLOBALE : Si l'utilisateur clique n'importe où ailleurs 
            // sur la page (en dehors du menu), on referme automatiquement le Dropdown.
            document.addEventListener('click', (e) => {
                if (!dropdownTrigger.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('open');
                    dropdownTrigger.classList.remove('arrow-rotated');
                }
            });
        }

        // 3. Sécurité de redimensionnement (inchangé)
        this.mediaQueryMobile.addEventListener('change', (e) => {
            if (!e.matches) {
                if (dropdownMenu) dropdownMenu.classList.remove('open');
                if (dropdownTrigger) dropdownTrigger.classList.remove('arrow-rotated');
                this.close();
            }
        });
    }

    toggleMenu() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.open();
        } else {
            this.close();
        }
    }

    open() {
        this.isOpen = true;
        this.navMenu.classList.add('menu-active');
        this.burgerBtn.setAttribute('aria-expanded', 'true');
    }

    close() {
        this.isOpen = false;
        if (this.navMenu) this.navMenu.classList.remove('menu-active');
        if (this.burgerBtn) this.burgerBtn.setAttribute('aria-expanded', 'false');

        // 🌟 SÉCURITÉ DESKTOP & SURFACE : Quand le système demande la fermeture globale 
        // (par exemple lors du clic sur un lien intercepté par le Scroll),
        // on nettoie d'office les états du dropdown pour le fermer proprement sur PC.
        const dropdownTrigger = document.querySelector('.dropdown-trigger');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        if (dropdownMenu) dropdownMenu.classList.remove('open');
        if (dropdownTrigger) dropdownTrigger.classList.remove('arrow-rotated');
    }
}
