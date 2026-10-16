<?php

declare(strict_types=1);

namespace App\Core\Http;

final class Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {

        $this->headers['X-Content-Type-Options'] = 'nosniff';
        $this->headers['X-Frame-Options'] = 'DENY';
        $this->headers['X-XSS-Protection'] = '1; mode=block';
        // Ajout indispensable pour les sites de santé : protection du référencement croisé
        $this->headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function send(): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }

        echo $this->content;
        
        // CORRECTIF : Arrêt propre du processus PHP pour empêcher les effets de bord post-rendu
        exit;
    }
}