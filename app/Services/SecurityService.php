<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Http\Session;

/**
 * Service d'élite dédié à la protection cryptographique de l'application (CSRF).
 */
final class SecurityService
{
    private const CSRF_KEY = '_csrf_token';

    /**
     * Le Container injectera automatiquement le composant Session ici par réflexion.
     */
    public function __construct(
        private readonly Session $session
    ) {}

        /**
     * Génère un jeton CSRF fort s'il n'existe pas déjà en session, puis le retourne.
     */
    public function generateToken(): string
    {
        if (!$this->session->has(self::CSRF_KEY)) {
            $token = bin2hex(random_bytes(32));
            $this->session->set(self::CSRF_KEY, $token);
        }

        $storedToken = $this->session->get(self::CSRF_KEY);

        return is_string($storedToken) ? $storedToken : '';
    }

    /**
     * Valide le jeton soumis contre le jeton stocké de manière sécurisée (Anti-Timing Attack).
     */
    public function validateToken(?string $submittedToken): bool
    {
        if ($submittedToken === null) {
            return false;
        }

        $storedToken = $this->session->get(self::CSRF_KEY);

        if (!is_string($storedToken)) {
            return false;
        }

        // Vérification en temps constant (la règle d'or de la protection OWASP)
        return hash_equals($storedToken, $submittedToken);
    }
}
