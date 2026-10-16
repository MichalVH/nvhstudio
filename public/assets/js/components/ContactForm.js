/**
 * ==========================================================================
 * NVHFAST - COMPOSANT POO VALIDATION ET SOUMISSION AJAX CONTACT
 * ==========================================================================
 * Responsabilité unique : Intercepter le formulaire, valider et piloter l'UI.
 */
export class ContactForm {

    constructor() {
        this.form = null;
        this.errorContainer = null;
        this.errorMessage = null;
        this.submitButton = null; // Cible du bouton
        this.originalButtonText = ''; // Sauvegarde du texte initial
    }

    /**
     * Point d'entrée obligatoire pour amorcer le composant.
     */
    init() {
        this.form = document.getElementById('contact-form');

        if (this.form) {
            this.submitButton = this.form.querySelector('button[type="submit"]');
            if (this.submitButton) {
                this.originalButtonText = this.submitButton.innerHTML;
            }
            this.prepareErrorContainer();
            this.registerEvents();
        }
    }

    /**
     * Prépare et insère dynamiquement le conteneur d'erreurs s'il n'existe pas.
     */
    prepareErrorContainer() {
        this.errorContainer = this.form.querySelector('.js-ajax-error-container');

        if (!this.errorContainer) {
            this.errorContainer = document.createElement('div');
            this.errorContainer.className = 'js-ajax-error-container';
            this.errorContainer.style.cssText = "display: none; background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 500;";

            this.errorMessage = document.createElement('span');
            this.errorContainer.textContent = '⚠️ ';
            this.errorContainer.appendChild(this.errorMessage);

            this.form.insertBefore(this.errorContainer, this.form.firstChild);
        } else {
            this.errorMessage = this.errorContainer.querySelector('.js-ajax-error-message');
        }
    }

    /**
     * Établit les écouteurs d'événements.
     */
    registerEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    /**
     * Orchestre la soumission asynchrone du formulaire.
     */
    async handleSubmit(e) {
        e.preventDefault();

        this.errorContainer.style.display = 'none';
        this.toggleLoadingState(true); // Bloque le bouton à la soumission

        const formData = new FormData(this.form);

        try {
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                this.handleSuccess(result.message);
            } else {
                this.handleError(result.message);
            }

        } catch (error) {
            this.handleError("Une erreur de connexion est survenue. Veuillez réessayer.");
        } finally {
            this.toggleLoadingState(false); // Libère le bouton dans tous les scénarios
        }
    }

    /**
     * Active ou désactive l'état visuel de chargement du bouton.
     */
    toggleLoadingState(isLoading) {
        if (!this.submitButton) return;

        if (isLoading) {
            this.submitButton.disabled = true;
            this.submitButton.innerHTML = `
                <span style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg style="animate-spin; animation: spin 1s linear infinite;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"/>
                        <path d="M4 12a8 8 0 0 1 8-8V4"/>
                    </svg>
                    Envoi en cours...
                </span>
            `;
            // Déclaration du style d'animation inline pour éviter les dépendances CSS globales
            if (!document.getElementById('js-spin-style')) {
                const style = document.createElement('style');
                style.id = 'js-spin-style';
                style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
                document.head.appendChild(style);
            }
        } else {
            this.submitButton.disabled = false;
            this.submitButton.innerHTML = this.originalButtonText;
        }
    }

    /**
     * Gère la réussite de la requête et affiche la modale premium.
     */
    handleSuccess(message) {
        this.form.reset();

        const oldModal = document.getElementById('success-modal');
        if (oldModal) oldModal.remove();

        const modalHTML = `
            <div id="success-modal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modal-title">
                <div class="modal-wrapper">
                    <div class="modal-icon">
                        <svg xmlns="http://w3.org" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 id="modal-title" class="modal-title">Demande transmise !</h3>
                    <p class="modal-text">${message}</p>
                    <button type="button" class="btn btn-primary btn-modal-close" id="js-modal-close">
                        Fermer la fenêtre
                    </button>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        document.getElementById('js-modal-close').addEventListener('click', () => {
            document.getElementById('success-modal').remove();
            window.location.href = '/';
        });
    }

    /**
     * Gère l'affichage dynamique de l'erreur sans bouger l'écran.
     */
    handleError(message) {
        this.errorMessage.textContent = message;
        this.errorContainer.style.display = 'block';
    }
}
