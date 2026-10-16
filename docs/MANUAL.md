# 📘 NVHFAST — Manuel d'Utilisation Technique & Guide de l'Opérateur (v1.0)

Ce guide fournit les procédures pas-à-pas et les structures de code complètes pour modifier, étendre et industrialiser le framework **NVHFAST**. Il est conçu pour être immédiatement actionnable, même par un développeur junior ou un intégrateur débutant au sein de la marque **NVH Studio**.

---

## 🧭 FICHE 1 : Modifier le contenu d'une section existante (One-Page)

Le site d'accueil est découpé sous forme de fragments HTML isolés (appelés "Partials") gérés par le moteur de rendu Twig.

### Procédure :
1. Ouvrez votre explorateur de fichiers ou VS Code et naviguez dans le dossier : `templates/partials/`.
2. Ouvrez le fichier correspondant à la zone que vous souhaitez modifier :
   * `_hero.html.twig` : Titre principal, slogan d'accroche et boutons d'action (CTA).
   * `_expertise.html.twig` : Grille des services et compétences métiers de l'entreprise.
   * `_about.html.twig` : Texte de présentation de la marque et zone de médias (images/vidéos).
   * `_testimonials.html.twig` : Cartes des avis clients et éléments de réassurance sociale.
   * `_contact.html.twig` : Structure du formulaire de capture de leads.
3. Modifiez directement le texte ou les structures HTML.
4. **Règle d'or de performance (Lighthouse) :** Si vous ajoutez une balise image (`<img>`), ajoutez obligatoirement l'attribut `loading="lazy"` à l'intérieur de la balise pour maintenir notre promesse de vitesse atomique.
5. Enregistrez le fichier. Vos modifications apparaissent instantanément à l'écran (le cache se recompile automatiquement en mode développement).

---

## 🗺️ FICHE 2 : Ajouter ou Modifier un lien dans le menu de navigation

Le menu d'en-tête (Desktop) et le menu burger déroulant (Mobile) partagent la même liste sémantique.

### Procédure :
1. Ouvrez le fichier `templates/partials/_navigation.html.twig`.
2. Pour ajouter un nouvel onglet pointant vers une section de votre page d'accueil, ajoutez une ligne sous la forme d'un élément de liste `<li>` en respectant scrupuleusement cette syntaxe dynamique :
   ```html
   <li><a href="{{ app.base_path }}/#mon-id-section">Mon Nouvel Onglet</a></li>
   ```
   *Note : Le préfixe `{{ app.base_path }}` est indispensable pour maintenir le mode Caméléon et garantir que le lien fonctionne aussi bien sous l'URL `.test` que sous l'URL profonde `localhost/nvhfast/public/`.*

---

## 🧱 FICHE 3 : Ajouter une NOUVELLE SECTION sur la page d'accueil

Pour insérer un tout nouveau bloc thématique (par exemple, une section "FAQ" ou "Tarifs") au sein de la Landing Page.

### Procédure :
1. Créez un nouveau fichier dans le dossier `templates/partials/` nommé exactement `_faq.html.twig`.
2. Ouvrez ce fichier vide et collez-y la structure de base suivante :
   ```html
   <section id="faq">
       <div class="container">
           <h2 class="section-title">Foire Aux Questions</h2>
           <p style="text-align: center; color: var(--text-secondary);">
               Trouvez des réponses immédiates à vos questions.
           </p>
       </div>
   </section>
   ```
3. Ouvrez le fichier d'assemblage principal `templates/home.html.twig`.
4. Localisez l'endroit exact où vous souhaitez insérer cette section (par exemple, juste au-dessus du formulaire de contact) et ajoutez cette ligne d'inclusion Twig :
   ```twig
   {% include 'partials/_faq.html.twig' %}
   ```
5. Enregistrez. La section s'insère automatiquement dans le flux. L'aimantation douce au pouce (*Scroll Snap*) mobile s'appliquera automatiquement sans aucune configuration CSS supplémentaire, car notre règle cible génériquement toutes les balises `<section>`.

## 🌐 FICHE 4 : Créer une NOUVELLE PAGE isolée (avec sa propre URL)

Pour ajouter une page totalement indépendante du One-Page (à l'image de la page des Mentions Légales) possédant son propre chemin d'URL (ex: `/politique-confidentialite`).

### Procédure Étape par Étape :

#### Étape 4.1 : Déclaration de la Route
Ouvrez le fichier de cartographie des URLs : **`config/routes.php`**. Ajoutez la ligne suivante tout en bas du fichier pour enregistrer l'adresse de la page :
```php
$router->map('GET', '/politique-confidentialite', 'PageController#privacy', 'privacy_policy');
```

#### Étape 4.2 : Ajout de la logique dans le Contrôleur
Ouvrez votre fichier **`app/Controllers/PageController.php`**. Ajoutez la méthode `privacy()` à l'intérieur de la classe pour qu'elle charge notre futur template HTML :
```php
    /**
     * Gère l'affichage de la page Politique de Confidentialité.
     */
    public function privacy(): Response
    {
        // 1. Compilation du template par le moteur Twig
        $html = $this->renderer->render('privacy.html.twig');

        // 2. Renvoi de la réponse HTTP 200 propre au navigateur
        return (new Response())->setContent($html);
    }
```

#### Étape 4.3 : Création de la Vue HTML Twig
Créez un nouveau fichier nommé exactement **`privacy.html.twig`** directement dans le dossier racine **`templates/`**. Collez-y ce code complet pour hériter du design global :
```twig
{% extends 'layout.html.twig' %}

{% block content %}
<main class="section-legal">
    <div class="container-legal">
        <header class="header-legal">
            <h1>Politique de Confidentialité</h1>
            <p class="date-legal">Dernière mise à jour : 2026</p>
        </header>

        <article class="article-legal">
            <h2>1. Collecte des données</h2>
            <p>
                Dans le cadre de l'utilisation du framework NVHFAST, les données collectées via le formulaire de contact (Nom, Email, Téléphone) sont exclusivement destinées à la gestion de la relation client.
            </p>
            
            <h2>2. Hébergement et Sécurité</h2>
            <p>
                Toutes les données sont stockées de manière sécurisée en base de données conformément aux directives européennes du RGPD.
            </p>
        </article>
    </div>
</main>
{% endblock %}
```

---

## 📑 FICHE 5 : Ajouter un NOUVEAU FORMULAIRE de capture sécurisé

Pour implémenter un second formulaire de traitement (par exemple, un formulaire de "Demande de Devis") capable d'intercepter les données, de valider la sécurité et d'enregistrer les informations en BDD.

### Procédure Étape par Étape :

#### Étape 5.1 : Déclaration de la Route d'Interception
Ouvrez le fichier **`config/routes.php`** et ajoutez une route acceptant strictement le verbe `POST` pour intercepter la soumission :
```php
$router->map('POST', '/devis', 'DevisController#submit', 'devis_submit');
```

#### Étape 5.2 : Intégration du Visuel HTML dans votre Fragment Twig
Créez ou modifiez un fragment HTML Twig (ex: `templates/partials/_devis.html.twig`). Utilisez scrupuleusement cette structure pour intégrer le bouclier CSRF obligatoire :
```html
<form action="{{ app.base_path }}/devis" method="POST" novalidate>
    
    {# --- BOUCLIER DE SÉCURITÉ CSRF INTERNE --- #}
    <input type="hidden" name="_csrf_token" value="{{ csrf_token() }}">

    <div class="form-group">
        <label for="company" class="form-label">Nom de l'entreprise</label>
        <input type="text" id="company" name="company" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="budget" class="form-label">Budget estimé (€)</label>
        <input type="number" id="budget" name="budget" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Demander mon devis</button>
</form>
```

#### Étape 5.3 : Création du Contrôleur Dédié
Créez un nouveau fichier nommé exactement **`DevisController.php`** à l'intérieur du dossier **`app/Controllers/`**. Collez-y ce code d'architecture pure. L'Autowiring de votre noyau va injecter automatiquement toutes les dépendances :

```php

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Http\Session;
use App\Services\SecurityService;

/**
 * Contrôleur gérant l'interception et le traitement des demandes de devis.
 */
final class DevisController
{
    /**
     * Le Container injecte automatiquement ces 3 briques d'infrastructure par réflexion.
     */
    public function __construct(
        private readonly SecurityService $securityService,
        private readonly Request $request,
        private readonly Session $session
    ) {}

    /**
     * Traite la soumission POST du formulaire de devis.
     */
    public function submit(): Response
   {
        $response = new Response();
        referer = this->request->serverData['HTTP_REFERER'] ?? '/';

        // 1. Extraction et vérification immédiate du bouclier anti-CSRF
        submittedToken = this->request->postData['_csrf_token'] ?? null;
        if (!this->securityService->validateToken(submittedToken)) {
            return $response->setStatusCode(403)
                            ->setContent("<h1>❌ Erreur 403 - Jeton de sécurité CSRF invalide.</h1>");
        }

        // 2. Capture des champs du formulaire nettoyés des failles XSS
        company = trim(this->request->postData['company'] ?? '');
        budget = trim(this->request->postData['budget'] ?? '');

        // 3. Validation métier élémentaire
        if ($company === '' || \(budget === '') {\)this->session->setFlash('error', "Veuillez remplir l'ensemble des champs du devis.");
            return response->setStatusCode(302)->setHeader('Location', referer);
   }

        // 4. Stockage du message de succès dans la Session Flash
        $this->session->setFlash('success', "Votre demande de devis pour l'entreprise {\$company} a été prise en compte !");

        // 5. Redirection étanche pour déclencher l'ouverture asynchrone de la modale
        return response->setStatusCode(302)->setHeader('Location', referer);
}
```
