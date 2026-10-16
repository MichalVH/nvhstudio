CONTEXTE D'INGÉNIERIE ÉLITE — FRAMEWORK NVHFAST (NVH STUDIO)

Tu agis en tant que Lead Architect PHP/JS Senior. Nous auditions le framework sur-mesure "NVHFAST", un Starter Pack d'élite facturé 1400€+ pour des sites vitrines One-Page ultra-performants et sécurisés. 

L'infrastructure complète est fonctionnelle de bout en bout en local (Laragon, double URL .test et localhost) et l'envoi de mail HTML via PHPMailer SMTP (Gmail/Mailtrap) est validé. La persistance SQL fonctionne sur HeidiSQL.

Voici la cartographie exacte de l'arborescence validée du projet :
- app/Core/ : Exception/, Http/ (Request.php, Response.php, Session.php [OWASP hardened: HttpOnly, SameSite=Strict]), Router/ (Route.php, Router.php), App.php (Bootstrap), Container.php (Autowiring récursif via Reflection API), Database.php (Connexion PDO brute, ATTR_EMULATE_PREPARES désactivé).
- app/Services/ : ContactService.php (Validation métier, requêtes préparées strictes), MailerService.php (Gabarit HTML Inline, PHPMailer SMTP), SecurityService.php (Jetons CSRF 64 caractères, hash_equals).
- app/Controllers/ : HomeController.php, PageController.php, ContactController.php (Pattern PRG 302, capture des InvalidArgumentException avec Session Flash).
- config/ : routes.php (GET /, GET /mentions-legales, POST /contact), services.php (Recettes manuelles des singletons Session, Security, Renderer).
- public/assets/ : css/style.css (Variables atomiques, Scroll Snap mobile 0.45s), js/app.js (Bootstrap), js/components/Navigation.js (POO JS native, menu burger, accessibilité aria-expanded).
- templates/ : home.html.twig, layout.html.twig (Modale flash asynchrone), legal.html.twig + fragments dans partials/ (dont _contact.html.twig avec champ masqué CSRF et bandeau error).
- Racine : .env (Variables de prod/dev), README.md (Spécification industrielle), MANUAL.md (Guide de l'opérateur junior en 5 fiches).

PROMISES DU FRAMEWORK À AUDITER :
1. Vitesse (100/100 Lighthouse, cache Twig figé en prod, lazy-loading natif des images, Classmap Composer -o).
2. Sécurité OWASP radicale (Immunité totale contre les 4 grandes failles : XSS, Injections SQL via requêtes préparées natives, CSRF temps constant, Vol de Session).
3. Portabilité (Mode Caméléon sur les deux URLs sans aucune reconfiguration).
4. Maintenabilité (Découplage strict, SOLID, POO JS modulaire, Autowiring).
5. Scalabilité (BIGINT UNSIGNED sur les clés primaires).

OBJECTIF IMMÉDIAT DE LA CONVERSATION :
Lance un audit total et méthodique, dossier par dossier, fichier par fichier. Analyse la cohérence globale, traque les angles morts, vérifie que les namespaces, les typages stricts (declare(strict_types=1)) et les conventions de nommage s'alignent sur les standards industriels AAA. Dis-moi par quel fichier ou dossier du Core nous démarrons l'inspection chirurgicale.
