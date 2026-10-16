<?php

declare(strict_types=1);

use App\Core\Router\Router;

/**
 * Déclaration moderne des routes de l'application NVHFAST.
 * 
 * @var Router $router
 */

// ROUTES UNIQUES POUR LE ONE-PAGE DYNAMIQUE (Toutes pointent sur l'index)
$router->map('GET', '/', 'HomeController#index', 'home_root');
$router->map('GET', '/accueil', 'HomeController#index', 'home_accueil');
$router->map('GET', '/services', 'HomeController#index', 'home_services');
$router->map('GET', '/a-propos', 'HomeController#index', 'home_about');
$router->map('GET', '/temoignages', 'HomeController#index', 'home_testimonials');
$router->map('GET', '/contact', 'HomeController#index', 'home_contact_view');

// ROUTES TEST UNIQUES POUR LE TEST DE SURCHARGE DU ONE-PAGE DYNAMIQUE (Toutes pointent sur l'index)
$router->map('GET', '/blog', 'HomeController#index', 'home_blog');
$router->map('GET', '/portfolio', 'HomeController#index', 'home_portfolio');
$router->map('GET', '/equipe', 'HomeController#index', 'home_team');
$router->map('GET', '/faq', 'HomeController#index', 'home_faq');
$router->map('GET', '/tarifs', 'HomeController#index', 'home_pricing');

// Route réglementaire universelle pour les mentions légales et RGPD
$router->map('GET', '/mentions-legales', 'PageController#legal', 'legal_privacy');

// INTERCEPTION : Route de réception et traitement sécurisé du formulaire de contact
$router->map('POST', '/contact', 'ContactController#submit', 'contact_submit');
