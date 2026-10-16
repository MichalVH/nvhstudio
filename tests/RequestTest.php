<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Http\Request;

/**
 * Test unitaire validant les comportements de nettoyage de la classe Request.
 */
final class RequestTest extends TestCase
{
    /**
     * Valide que getMethod() normalise toujours le verbe HTTP en majuscules.
     */
    public function testGetMethodReturnsUpperCasedString(): void
    {
        // On passe volontairement une méthode en minuscules dans serverData
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'post'
        ]);

        // Ton code doit appliquer strtoupper() et retourner 'POST'
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * Valide le comportement de repli par défaut de getMethod().
     */
    public function testGetMethodFallbackToGetWhenMissingOrInvalid(): void
    {
        // Cas 1 : REQUEST_METHOD est complètement absent
        $requestMissing = new Request([], [], []);
        $this->assertEquals('GET', $requestMissing->getMethod());

        // Cas 2 : REQUEST_METHOD n'est pas une chaîne (ex: un tableau ou entier)
        $requestInvalid = new Request([], [], [
            'REQUEST_METHOD' => 123
        ]);
        $this->assertEquals('GET', $requestInvalid->getMethod());
    }

    /**
     * Valide que getPath() isole l'URI brute et supprime proprement les query parameters.
     */
    public function testGetPathCleansQueryParamsFromUri(): void
    {
        // On simule une URI complexe contenant des paramètres ?id=42
        $request = new Request([], [], [
            'REQUEST_URI' => '/boutique/produit?id=42&couleur=rouge'
        ]);

        // Ton code utilise explode('?', ...) et doit extraire uniquement le chemin logique
        $this->assertEquals('/boutique/produit', $request->getPath());
    }

    /**
     * Valide le comportement de repli de getPath() sur la racine.
     */
    public function testGetPathFallbackToSlashWhenMissingOrEmpty(): void
    {
        // Cas 1 : REQUEST_URI est complètement absent
        $requestMissing = new Request([], [], []);
        $this->assertEquals('/', $requestMissing->getPath());

        // Cas 2 : REQUEST_URI est une chaîne vide après découpe (cas limite de explode)
        $requestEmpty = new Request([], [], [
            'REQUEST_URI' => ''
        ]);
        $this->assertEquals('/', $requestEmpty->getPath());
    }
}
