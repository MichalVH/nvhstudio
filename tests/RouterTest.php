<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Router\Router;
use App\Core\Http\Request;

/**
 * Test d'intégration validant le comportement étanche du moteur de routage.
 */
final class RouterTest extends TestCase
{
    public function testRouterCanMatchRegisteredRouteAndExtractComponents(): void
    {
        // 1. On instancie ton routeur personnalisé
        $router = new Router();

        // 2. On déclare une route de test avec le format requis "Controller#method"
        $router->map('GET', '/contact-test-unitaire', 'ContactController#submit', 'contact_test');

        // 3. On simule la requête HTTP correspondante
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/contact-test-unitaire'
        ]);

        // 4. On demande la correspondance au routeur
        $routeMatch = $router->match($request);

        // 5. Assertions : On valide le résultat mathématiquement en lisant les propriétés publiques
        $this->assertNotNull($routeMatch, "Le routeur aurait dû trouver une correspondance pour /contact-test-unitaire");
        $this->assertEquals('App\\Controllers\\ContactController', $routeMatch->controller);
        $this->assertEquals('submit', $routeMatch->method);
    }

    public function testRouterReturnsNullWhenRouteDoesNotExist(): void
    {
        // 1. On instancie un routeur vierge
        $router = new Router();

        // 2. On déclare une route existante pour s'assurer que le routeur fonctionne
        $router->map('GET', '/home', 'HomeController#index', 'home');

        // 3. On simule une requête sur une URI qui n'existe absolument pas
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/page-fantome-qui-n-existe-pas'
        ]);

        // 4. On demande la correspondance au routeur
        $routeMatch = $router->match($request);

        // 5. Assertion : Le routeur doit renvoyer null de manière étanche
        $this->assertNull($routeMatch, "Le routeur aurait dû renvoyer null pour une route inexistante");
    }

}
