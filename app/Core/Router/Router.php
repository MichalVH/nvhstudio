<?php

declare(strict_types=1);

namespace App\Core\Router;

use AltoRouter;
use App\Core\Http\Request;
use App\Core\Exception\ContainerException;

/**
 * Wrapper étanche autour d'AltoRouter assurant l'isolation du moteur de routage.
 */
final class Router
{
    private AltoRouter $altoRouter;

    public function __construct()
    {
        $this->altoRouter = new AltoRouter();
        $this->configureBasePath();
    }

    private function configureBasePath(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        
        $requestUriString = is_string($requestUri) ? $requestUri : '/';
        $scriptNameString = is_string($scriptName) ? $scriptName : '';

        $basePath = dirname($scriptNameString);
        $cleanBasePath = str_replace('\\', '/', $basePath);

        if (strpos($requestUriString, $cleanBasePath) !== 0) {
            return;
        }

        if ($cleanBasePath === '' || $cleanBasePath === '/' || $cleanBasePath === '.') {
            return;
        }

        $this->altoRouter->setBasePath($cleanBasePath);
    }

    public function map(string $method, string $route, string $target, ?string $name = null): self
    {
        try {
            $this->altoRouter->map($method, $route, $target, $name);
        } catch (\Exception $e) {
            throw new ContainerException("Erreur lors de la déclaration de la route [{$route}] : " . $e->getMessage(), 0, $e);
        }
        
        return $this;
    }

    public function match(Request $request): ?RouteMatch
    {
        $requestUrl = $request->getPath();
        $requestMethod = $request->getMethod();

        $match = $this->altoRouter->match($requestUrl, $requestMethod);

        if (!is_array($match)) {
            return null;
        }

        $parts = explode('#', (string)$match['target']);
        
        if (count($parts) !== 2) {
            throw new ContainerException("Le format de la cible pour la route '{$requestUrl}' est invalide. Attendu : 'Controller#method'.");
        }

        [$controllerName, $methodName] = $parts;

        // ÉVOLUTIVITÉ : Supporte à la fois un namespace racine ou un sous-contrôleur complet
        $fullyQualifiedController = str_contains($controllerName, '\\') 
            ? $controllerName 
            : "App\\Controllers\\" . $controllerName;

        /** @var class-string $fullyQualifiedController */
        return new RouteMatch(
            $fullyQualifiedController,
            $methodName,
            /** @var array<string, string> $match['params'] */
            $match['params'] ?? []
        );
    }
}
