<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Http\Session;
use App\Services\SecurityService;

/**
 * Test unitaire automatisé certifiant le comportement étanche du bouclier anti-CSRF OWASP.
 */
final class SecurityServiceTest extends TestCase
{
    protected function setUp(): void
    {
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testSecurityServiceGeneratesStrongTokenAndValidatesIt(): void
    {
        $session = new Session();
        $securityService = new SecurityService($session);

        // 1. Génération du jeton
        $token = $securityService->generateToken();
        $this->assertNotEmpty($token);

        // 2. Validation avec le bon jeton -> DOIT passer
        $this->assertTrue($securityService->validateToken($token));

        // 3. Validation avec un faux jeton -> DOIT être bloqué
        $this->assertFalse($securityService->validateToken('un-faux-jeton-malveillant'));

        // 4. Validation avec un jeton vide ou null -> DOIT être bloqué
        $this->assertFalse($securityService->validateToken(null));
        $this->assertFalse($securityService->validateToken(''));
    }
}
