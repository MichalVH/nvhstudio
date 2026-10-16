<?php

declare(strict_types=1);

namespace App\Core\Http;

/**
 * Représentation immuable et sécurisée de la requête HTTP entrante.
 */
final readonly class Request
{
    /**
     * @param array<string, mixed> $queryParams
     * @param array<string, mixed> $postData
     * @param array<string, mixed> $serverData
     */
    public function __construct(
        public array $queryParams,
        public array $postData,
        public array $serverData
    ) {}

    public static function createFromGlobals(): self
    {
        return new self($_GET, $_POST, $_SERVER);
    }

    public function getMethod(): string
    {
        $method = $this->serverData['REQUEST_METHOD'] ?? 'GET';
        return is_string($method) ? strtoupper($method) : 'GET';
    }

    /**
     * Retourne l'URI de la requête nettoyée des paramètres de requête.
     */
    public function getPath(): string
    {
        $uri = $this->serverData['REQUEST_URI'] ?? '/';
        $uriString = is_string($uri) ? $uri : '/';
        $path = explode('?', $uriString);
        return $path[0] === '' ? '/' : $path[0];
    }
}
