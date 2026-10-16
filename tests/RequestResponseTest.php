<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Http\Request;
use App\Core\Http\Response;

/**
 * Test d'intégration validant l'isolation HTTP et les entêtes de protection OWASP.
 */
final class RequestResponseTest extends TestCase
{
    public function testRequestIsolatesMethodAndCleansPath(): void
    {
        // On simule des données issues de $_SERVER
        $serverData = [
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/contact?name=valeur&origin=google'
        ];

        $request = new Request([], [], $serverData);

        // Assertions sur la requête
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/contact', $request->getPath());
    }

    public function testResponseInjectsMandatoryHealthSecurityHeaders(): void
    {
        // On instancie une réponse standard vide
        $response = new Response('Contenu de test', 200, []);

        // Pour inspecter les headers sans envoyer la réponse, on utilise la Réflexion PHP
        $reflection = new \ReflectionClass($response);
        $property = $reflection->getProperty('headers');
        $property->setAccessible(true);
        
        /** @var array<string, string> $headers */
        $headers = $property->getValue($response);

        // Assertions de sécurité OWASP (Tes promesses clients de santé)
        $this->assertArrayHasKey('X-Content-Type-Options', $headers);
        $this->assertEquals('nosniff', $headers['X-Content-Type-Options']);

        $this->assertArrayHasKey('X-Frame-Options', $headers);
        $this->assertEquals('DENY', $headers['X-Frame-Options']);

        $this->assertArrayHasKey('X-XSS-Protection', $headers);
        $this->assertEquals('1; mode=block', $headers['X-XSS-Protection']);

        $this->assertArrayHasKey('Referrer-Policy', $headers);
        $this->assertEquals('strict-origin-when-cross-origin', $headers['Referrer-Policy']);
    }
}
