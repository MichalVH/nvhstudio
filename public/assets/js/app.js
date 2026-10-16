import { Navigation } from './components/Navigation.js';
import { Menu } from './components/Menu.js';
import { ContactForm } from './components/ContactForm.js';

document.addEventListener('DOMContentLoaded', () => {

    // 1. Initialisation de ton menu personnalisé
    const menu = new Menu();
    menu.init();

    // 2. Initialisation du scroll avec branchement du callback de fermeture
    const navigation = new Navigation(() => {
        menu.close(); 
    });
    navigation.init();

    // 3. Gestionnaire de contact AJAX (inchangé)
    const contactForm = new ContactForm();
    contactForm.init();
});
