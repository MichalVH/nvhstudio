# 🚀 NVHFAST — Framework & Starter Pack d'Élite (Architecture Spécification v1.0)

**NVHFAST** est un micro-framework PHP/JavaScript moderne de niveau industriel, conçu sur-mesure par et pour **NVH Studio**. Élaboré pour briser la dépendance aux usines à gaz monolithiques du marché, cet écosystème sert de socle technologique pour le déploiement ultra-rapide de sites vitrines d'élite et de Landing Pages à haute conversion facturés **1 400 €+**.

---

## 🏛️ 1. Présentation & Vision Métier

### À quoi sert NVHFAST ?
Dans le paysage du développement web moderne, les agences font face à un dilemme permanent : utiliser un CMS lourd (WordPress) ou un framework surdimensionné (Symfony, Laravel) qui dégradent les performances et complexifient la maintenance, ou coder en PHP procédural brut au détriment de la sécurité et du découplage.

**NVHFAST résout cette équation.** Il offre aux développeurs et repreneurs un environnement de travail industrialisé qui combine la structure stricte de la Programmation Orientée Objet (POO) et des composants découplés, tout en maintenant un poids plume et des vitesses d'exécution atomiques. C'est l'arme absolue pour livrer un site au pixel près, entièrement sécurisé et sans aucune dette technique, en un temps record.

---

# 🚀 NVHFAST — Framework & Starter Pack d'Élite (Architecture Spécification v1.1)

**NVHFAST** est un micro-framework PHP/JavaScript moderne de niveau industriel, conçu sur-mesure par et pour **NVH Studio**. Élaboré pour briser la dépendance aux usines à gaz monolithiques du marché, cet écosystème sert de socle technologique pour le déploiement ultra-rapide de sites vitrines d'élite et de Landing Pages à haute conversion facturés **1 400 €+**.

---

## 🏛️ 1. Présentation & Vision Métier

### À quoi sert NVHFAST ?
Dans le paysage du développement web moderne, les agences font face à un dilemme permanent : utiliser un CMS lourd (WordPress) ou un framework surdimensionné (Symfony, Laravel) qui dégradent les performances et complexifient la maintenance, ou coder en PHP procédural brut au détriment de la sécurité et du découplage.

**NVHFAST résout cette équation.** Il offre aux développeurs et repreneurs un environnement de travail industrialisé qui combine la structure stricte de la Programmation Orientée Objet (POO) et des composants découplés, tout en maintenant un poids plume et des vitesses d'exécution atomiques. C'est l'arme absolue pour livrer un site au pixel près, entièrement sécurisé et sans aucune dette technique, en un temps record.

---

## 💎 2. Le Manifeste des Promesses Techniques (Les Piliers d'Élite)

### 🏎️ Vitesse & Performance Absolue
*   **Compilation Figée Twig :** En mode production (`APP_ENV=production`), le moteur Twig désactive l'interrogation du disque dur (`'auto_reload' => false`). Les gabarits HTML sont compilés une bonne fois pour toutes sous forme de classes PHP brutes dans `var/cache/`. Le temps de rendu serveur approche le **0.0ms**.
*   **Classmap d'Autowiring Optimisée :** L'exécution de la commande d'optimisation de l'autoloading de Composer cartographie à plat l'arbre complet des classes, permettant au conteneur de dépendances de s'exécuter instantanément en mémoire sans scan de répertoire.
*   **Lazy Loading Natif :** L'ensemble de l'infrastructure média et des visuels du projet est configuré pour exploiter le chargement différé (*Lazy Loading*). Les images ne sont téléchargées par le navigateur que lorsqu'elles entrent dans le champ de vision de l'utilisateur, ce qui préserve la bande passante sur mobile et garantit un score de **100/100** aux rapports de performance **Google PageSpeed / Lighthouse**.

### 🛡️ Le Rempart Sécurité Intégral (Traitement des 4 Grandes Menaces)
Le cœur de l'infrastructure de **NVHFAST** a été développé pour élever une barrière mathématique et architecturale contre les quatre vulnérabilités les plus critiques du web moderne :

1. **Les Injections SQL (SQLi) :** L'émulation des requêtes préparées PDO est explicitement débrayée (`PDO::ATTR_EMULATE_PREPARES => false`). Le pilote force l'utilisation des requêtes préparées natives du serveur MariaDB/MySQL, rendant l'injection de code malveillant structurellement impossible dans vos requêtes.
2. **Les Failles Cross-Site Scripting (XSS) :** L'objet `Request` filtre et isole nativement les superglobales d'entrée. De plus, l'encapsulation de Twig applique un échappement automatique de tous les caractères spéciaux affichés à l'écran, neutralisant toute tentative d'exécution de scripts JavaScript malveillants au sein des formulaires.
3. **Les Attaques Cross-Site Request Forgery (CSRF) :** Chaque formulaire embarque un jeton masqué à haute entropie (64 caractères hexadécimaux générés via `random_bytes()`). La validation s'effectue au niveau du contrôleur en temps constant via `hash_equals()` pour contrer les attaques temporelles (*Timing Attacks*).
4. **La Conformité Globale OWASP :** Au-delà des pare-feu applicatifs, **NVHFAST** protège l'intégrité de ses sessions en verrouillant les cookies au plus haut niveau des exigences du Top 10 de l'OWASP, en forçant dès le démarrage les directives `session.cookie_httponly = 1` (interdiction d'accès via JS) et `session.cookie_samesite = Strict` (interdiction de transmission inter-sites).

### ♿ Accessibilité (WCAG / SEO Compliant)
*   **Contrôle Sémantique Tactile :** L'ouverture du tiroir mobile modifie dynamiquement les attributs d'accessibilité `aria-expanded="true/false"`, permettant aux lecteurs d'écran d'interpréter le changement d'état du site en temps réel.
*   **Couvrance Opaque UI :** Le tiroir de navigation mobile utilise une opacité totale (`#ffffff`) et un index de superposition maximal (`z-index: 9999`) pour isoler visuellement le texte des liens et empêcher toute collision de lisibilité avec le flux inférieur du site.

### 🧩 Maintenabilité, Flexibilité & Mode Caméléon
*   **Double Adressage Universel (Mode Caméléon) :** Le framework intègre un calcul d'environnement dynamique au niveau du `Renderer`. L'application résout ses assets et intercepte ses requêtes de manière strictement identique sous une URL de domaine virtuel de type **`http://nvhfast.test`** ou sous une URL de sous-dossier locale profonde de type **`http://localhost/nvhfast/public/`**, sans nécessiter de reconfiguration manuelle.
*   **Container PSR-11 & Autowiring Récursif :** Aucune déclaration manuelle laborieuse des classes métiers n'est requise dans les fichiers de configuration. Le conteneur exploite la *Reflection API* pour analyser les signatures des constructeurs, fabriquer les dépendances requises et les injecter à la volée.
*   **Découplage Modulaire JavaScript :** Finis les fichiers de scripts gigantesques et désordonnés. L'écosystème utilise des modules natifs ES6 (`type="module"`). La classe `Navigation.js` possède la responsabilité unique de l'interface, orchestrée par le point d'entrée central `app.js`.

### 📈 Scalabilité Évolutive
*   **Clé Primaire Haute Capacité :** Toutes les tables du schéma (y compris `contacts`) utilisent des clés primaires typées `BIGINT UNSIGNED`. Cela élimine définitivement la barrière technique de saturation de l'auto-incrément à 2 milliards d'entrées présente sur les configurations classiques en `INT`.
*   **Pattern PRG (Post-Redirect-Get) :** L'interception du formulaire redirige instantanément l'utilisateur après traitement via un statut HTTP 302, protégeant la base de données contre les doubles insertions accidentelles si l'utilisateur rafraîchit son écran.

---

## 📁 3. L'Arborescence Complète & Rôle de Chaque Fichier

Voici la cartographie exacte et validée du projet **NVHFAST** :

```text
nvhfast/
├── app/                              # 📦 Espace de nommage racine "App\" (Autoload PSR-4)
│   ├── Controllers/                  # 🎮 Couche Contrôleur (Interception des flux HTTP)
│   │   ├── HomeController.php        # Affiche la Landing Page principale du site vitrine
│   │   ├── PageController.php        # Gère l'affichage des pages statiques réglementaires
│   │   └── ContactController.php     # Intercepte le POST du formulaire, valide le CSRF et orchestre les services
│   ├── Core/                         # 🏛️ Le Noyau d'Infrastructure (Invariable et étanche)
│   │   ├── Exception/                # Gestion centralisée des défaillances applicatives
│   │   ├── Http/                     # Objets sémantiques de la couche réseau
│   │   │   ├── Request.php           # Encapsule et nettoie automatiquements les superglobales ($_POST, $_GET, $_SERVER)
│   │   │   ├── Response.php          # Gère la structure des réponses (Entêtes HTTP, Codes Statuts 200/302/403, Contenu)
│   │   │   └── Session.php           # Initialise et durcit les sessions PHP avec les verrous OWASP (HttpOnly, SameSite)
│   │   ├── Router/                   # Moteur de routage et d'aiguillage des requêtes
│   │   │   ├── Route.php             # Représentation d'une correspondance URI / Verbe HTTP
│   │   │   └── Router.php            # Cartographie et compile l'ensemble des routes du framework
│   │   ├── App.php                   # Chef d'orchestre central chargeant le bootstrap, le container et le routeur
│   │   ├── Container.php             # Moteur d'injection de dépendances PSR-11 autonome avec Autowiring par Réflexion
│   │   └── Database.php              # Gestionnaire de connexion PDO brute, configuré contre les failles SQLi
│   └── Services/                     # ⚙️ Couche Services Métiers (Logique et traitements applicatifs)
│       ├── ContactService.php        # Valide la conformité des données du lead et applique l'INSERT en BDD
│       ├── MailerService.php         # Structure le gabarit HTML premium et expédie la notification via PHPMailer SMTP
│       └── SecurityService.php       # Génère et valide en temps constant les jetons de protection CSRF
├── config/                           # 🛠️ Zone de Configuration Système
│   ├── routes.php                    # Déclaration et cartographie des URIs admissibles (GET /, GET /mentions-legales, POST /contact)
│   └── services.php                  # Recettes de fabrication manuelles pour les services complexes (Database, Session, Renderer)
├── public/                           # 🌍 Point d'Entrée Unique Apache/Nginx (Le seul répertoire exposé au Web)
│   ├── assets/                       # Fichiers statiques et dynamiques de l'interface Front
│   │   ├── css/
│   │   │   └── style.css             # Design unifié, variables atomiques et Media Queries avec Scroll Snap fluide
│   │   └── js/
│   │       ├── components/
│   │       │   └── Navigation.js     # Classe POO gérant l'animation et l'accessibilité du menu burger mobile
│   │       └── app.js                # Point d'entrée unique JS attendant le chargement du DOM pour bootstrapper les modules
│   └── index.php                     # Fichier d'amorce universel chargeant l'Autoloader Composer et lançant l'instance App
├── templates/                        # 🎨 Couche Vues & Rendu Sémantique (Gérée par Twig)
│   ├── partials/                     # Éléments anatomiques et fragments réutilisables du One-Page
│   │   ├── _about.html.twig          # Section de présentation textuelle et média de la marque
│   │   ├── _contact.html.twig        # Section formulaire de contact sécurisée par le champ masqué CSRF
│   │   ├── _expertise.html.twig      # Section mettant en valeur le catalogue de services
│   │   ├── _hero.html.twig           # Section d'accroche principale à fort impact visuel
│   │   ├── _navigation.html.twig     # Fragment d'en-tête contenant le logo et le trigger burger réactif
│   │   └── _testimonials.html.twig   # Section de réassurance sociale affichant les avis clients
│   ├── home.html.twig                # Gabarit d'assemblage principal injectant tous les fragments du One-Page
│   ├── layout.html.twig              # Squelette HTML global injectant le viewport, les CSS, le module app.js et la modale Flash
│   └── legal.html.twig               # Page sémantique isolée dédiée aux mentions légales et RGPD
├── var/                              # 💾 Répertoire de Volatilité Système (Droits d'écriture requis CHMOD 775)
│   ├── cache/                        # Dossier où Twig écrit les fichiers PHP compilés en mode production
│   └── logs/                         # Journaux d'erreurs d'infrastructure pour l'audit sécurité
├── .env                              # 🔑 Fichier d'environnement réel contenant les secrets locaux (Ignoré par Git)
├── .env.example                      # Fichier blueprint servant de modèle pour les nouveaux développeurs
└── composer.json                     # Registre des dépendances du projet (Twig, PHPMailer) et configuration de l'Autoload
```

---

## 🚀 4. Guide d'Installation Rapide & Reprise de Projet

N'importe quel développeur ou repreneur de la marque **NVH Studio** peut installer et faire tourner le projet en moins de 2 minutes en suivant scrupuleusement ces 3 étapes :

### Étape 1 : Récupération des dépendances et optimisation
Ouvrez votre terminal local (Laragon), positionnez-vous à la racine du projet `nvhfast/` et lancez l'installation couplée à l'optimisation de la carte des classes :
```bash
composer require twig/twig phpmailer/phpmailer
composer dump-autoload -o
```

### Étape 2 : Amorce de l'environnement local
Générez votre fichier d'environnement réel en dupliquant le blueprint :
```bash
cp .env.example .env
```
Ouvrez le fichier `.env` nouvellement créé et assurez-vous d'y injecter vos identifiants de base de données Laragon ainsi que vos clés de bac à sable **Mailtrap** récupérées dans l'espace **Sandboxes > My Sandbox** :
```ini
APP_ENV=development
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_NAME=nvhfast_db
DB_USER=root
DB_PASS=

MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap_chiffres
MAIL_PASSWORD=votre_password_mailtrap_lettres
```

### Étape 3 : Déploiement du Schéma SQL de Production
Ouvrez votre outil **HeidiSQL**, connectez-vous à votre instance MariaDB/MySQL locale, ouvrez un nouvel onglet de requête (`Ctrl + T`) et exécutez ce script complet d'infrastructure :
```sql
CREATE DATABASE IF NOT EXISTS `nvhfast_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nvhfast_db`;

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_contacts_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🏎️ 5. Protocole de Passage en Production (Check-list Hébergeur)

Le jour de la livraison finale au client, créez le fichier `.env` sur le serveur de production distant et mettez à jour ces **3 uniques zones** pour verrouiller les performances et le réseau :

### 1. Figer l'infrastructure et activer la vitesse atomique
```ini
APP_ENV=production
APP_DEBUG=false
```
*   *Effet en conditions réelles :* Le compilateur Twig se fige, les erreurs PHP brutes sont masquées aux utilisateurs malveillants et redirigées vers `var/logs/`, maximisant le score Lighthouse.

### 2. Connecter la base de données de l'hébergeur
```ini
DB_NAME=nom_de_la_bdd_fournie_par_le_hebergeur
DB_USER=nom_utilisateur_fourni_par_le_hebergeur
DB_PASS=mot_de_passe_ultra_complexe_du_serveur
```

### 3. Activer la boîte mail professionnelle réelle du client (SMTP SSL)
```ini
MAIL_HOST=mail.domaine-client.com
MAIL_PORT=465
MAIL_USERNAME=contact@domaine-client.com
MAIL_PASSWORD=mot_de_passe_de_la_boite_pro
MAIL_ENCRYPTION=ssl
```

---
© 2026 **NVH Studio** — Tous droits réservés. L'excellence de l'ingénierie logicielle au service de la performance web.