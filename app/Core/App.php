<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Container;
use App\Core\Router\Router;

/**
 * Le cœur immuable du framework NVHFAST.
 */
final class App
{
    private Container $container;
    private Router $router;

    // CORRECTIF SOLID : Le conteneur et le routeur peuvent être injectés ou s'auto-instancier par défaut
    public function __construct(?Container $container = null, ?Router $router = null)
    {
        $this->container = $container ?? new Container();
        $this->router = $router ?? new Router();
        
        $this->bootstrap();
    }

    public function handle(Request $request): Response
    {
        $this->container->set(Request::class, $request);


        try {
            // CORRECTIF CRITIQUE ROUTAGE : Exportation locale de la variable attendue par config/routes.php
            $router = $this->router;

            if (file_exists(__DIR__ . '/../../config/routes.php')) {
                require_once __DIR__ . '/../../config/routes.php';
            }

            $routeMatch = $this->router->match($request);

        //  CODE Page 404 : Gestion améliorée avec rendu Twig si disponible
            if ($routeMatch === null) {
            $response = new Response();
                
            // 1. Récupération de votre wrapper de rendu NVHFAST
            $rendererInstance = $this->container->get(\App\Views\Renderer::class);
            $renderer = $rendererInstance instanceof \App\Views\Renderer ? $rendererInstance : null;

            if ($renderer !== null) {
            // 2. Rendu propre de votre template 404 via votre service
                $htmlContent = $renderer->render('errors/404.html.twig');
                return $response->setStatusCode(404)->setContent($htmlContent);
            }

                return $response->setStatusCode(404)
                                ->setContent("<h1>❌ Erreur 404 - Page introuvable</h1>");
            }

            $controllerName = $routeMatch->controller;
            $methodName = $routeMatch->method;

            $controllerInstance = $this->container->get($controllerName);
            $response = $controllerInstance->$methodName(...array_values($routeMatch->params));

            return $response;
            
        } catch (\Throwable $e) {
            $response = new Response();
            
            $environment = $_ENV['APP_ENV'] ?? 'production';
            
            // En mode développement : on affiche l'erreur technique pour coder
            if ($environment === 'development' || $environment === 'local' || $environment === 'test') {
                $message = "Erreur critique du système : " . $e->getMessage() . " dans " . $e->getFile() . " à la ligne " . $e->getLine();
                return $response->setStatusCode(500)->setContent($message);
            }
            
            // En mode production : on affiche ton gabarit Twig premium personnalisé
            $rendererInstance = $this->container->get(\App\Views\Renderer::class);
            $renderer = $rendererInstance instanceof \App\Views\Renderer ? $rendererInstance : null;

            if ($renderer !== null) {
                $htmlContent = $renderer->render('errors/500.html.twig');
                return $response->setStatusCode(500)->setContent($htmlContent);
            }
            
            return $response->setStatusCode(500)->setContent("<h1>❌ Une erreur interne est survenue.</h1>");
        }
    }

    private function bootstrap(): void
    {
        // On conserve l'amorce de session
        if (class_exists('\App\Core\Http\Session')) {
            new \App\Core\Http\Session();
        }

        // CHARGEMENT INDUSTRIEL : Alignement strict sur le namespace PSR-4 du composer.lock
        if (file_exists(__DIR__ . '/../../.env') && class_exists('\Dotenv\Dotenv')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        if (file_exists(__DIR__ . '/../../config/services.php')) {
            $configureServices = require_once __DIR__ . '/../../config/services.php';
            
            if (is_callable($configureServices)) {
                $configureServices($this->container);
            }
        }
    }
}
