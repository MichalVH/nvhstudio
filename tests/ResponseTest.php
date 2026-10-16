<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Http\Response;

/**
 * Test unitaire validant la configuration et la sécurité du composant Response.
 */
final class ResponseTest extends TestCase
{
    /**
     * Valide que le constructeur injecte systématiquement les 4 en-têtes de sécurité OWASP.
     */
    public function testResponseInjectsSecurityHeadersOnInitialization(): void
    {
        // 1. On instancie une réponse brute
        $response = new Response();

        // 2. On extrait la propriété privée "headers" par Réflexion PHP pour l'analyser
        $reflection = new \ReflectionClass($response);
        $property = $reflection->getProperty('headers');
        $property->setAccessible(true);
        
        /** @var array<string, string> $headers */
        $headers = $property->getValue($response);

        // 3. Assertions : Vérification chirurgicale des clés et valeurs de sécurité requises
        $this->assertArrayHasKey('X-Content-Type-Options', $headers);
        $this->assertEquals('nosniff', $headers['X-Content-Type-Options']);

        $this->assertArrayHasKey('X-Frame-Options', $headers);
        $this->assertEquals('DENY', $headers['X-Frame-Options']);

        $this->assertArrayHasKey('X-XSS-Protection', $headers);
        $this->assertEquals('1; mode=block', $headers['X-XSS-Protection']);

        // Alignement strict sur ton commentaire concernant les sites de santé
        $this->assertArrayHasKey('Referrer-Policy', $headers);
        $this->assertEquals('strict-origin-when-cross-origin', $headers['Referrer-Policy']);
    }

    /**
     * Valide le bon fonctionnement des méthodes fluides d'altération de la Response.
     */
    public function testFluencyOfSettersAndDataStorage(): void
    {
        $response = new Response();

        // On vérifie que les setters retournent bien $this (chaînage fluide)
        $updatedResponse = $response->setContent('Hello World')
                                    ->setStatusCode(201)
                                    ->setHeader('Content-Type', 'text/html');

        $this->assertSame($response, $updatedResponse);

        // Extraction par Réflexion pour s'assurer que les valeurs ont bien été écrites
        $reflection = new \ReflectionClass($response);
        
        $contentProperty = $reflection->getProperty('content');
        $contentProperty->setAccessible(true);
        $this->assertEquals('Hello World', $contentProperty->getValue($response));

        $statusProperty = $reflection->getProperty('statusCode');
        $statusProperty->setAccessible(true);
        $this->assertEquals(201, $statusProperty->getValue($response));

        $headersProperty = $reflection->getProperty('headers');
        $headersProperty->setAccessible(true);
        /** @var array<string, string> $headers */
        $headers = $headersProperty->getValue($response);
        $this->assertEquals('text/html', $headers['Content-Type']);
    }
}
