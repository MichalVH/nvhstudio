<?php

declare(strict_types=1);

namespace App\Core\Router;

/**
 * Représentation immuable du résultat d'un routage réussi (DTO).
 */
final readonly class RouteMatch
{
    /**
     * @param class-string $controller Nom pleinement qualifié de la classe (ex: App\Controllers\HomeController)
     * @param string $method Nom de la méthode à exécuter (ex: index)
     * @param array<string, string> $params Paramètres dynamiques extraits de l'URL (ex: ['id' => '12'])
     */
    public function __construct(
        public string $controller,
        public string $method,
        public array $params = []
    ) {}
}
