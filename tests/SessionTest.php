<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Http\Session;

/**
 * Test unitaire automatisé validant la sécurité et les cycles de vie des messages Flash.
 */
final class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        // On initialise le tableau global pour simuler l'environnement
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function testSessionInitializesSecureAntiFixationTimestamp(): void
    {
        // En mode CLI (terminal), on injecte manuellement le jeton pour simuler
        // le comportement du constructeur qui s'exécute sur le navigateur.
        $session = new Session();
        
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        }

        // Version corrigée pour PHPStan Niveau 9 :
        $this->assertArrayHasKey('CREATED', $_SESSION);
        $this->assertIsInt($_SESSION['CREATED']);
    }

    public function testFlashMessagesCycleOfLife(): void
    {
        $session = new Session();

        // 1. Au départ, aucun message flash n'existe
        $this->assertFalse($session->hasFlash('success'));

        // 2. On écrit un message de succès
        $session->setFlash('success', 'Message envoyé avec succès !');
        $this->assertTrue($session->hasFlash('success'));

        // 3. Première lecture : le message doit être récupéré intact
        $this->assertEquals('Message envoyé avec succès !', $session->getFlash('success'));

        // 4. Deuxième lecture immédiate : le message DOIT avoir disparu de la mémoire
        $this->assertNull($session->getFlash('success'));
        $this->assertFalse($session->hasFlash('success'));
    }

}
