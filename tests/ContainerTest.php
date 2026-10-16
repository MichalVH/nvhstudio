<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Core\Container;

/**
 * Test unitaire automatisé validant la robustesse du conteneur de dépendances.
 */
final class ContainerTest extends TestCase
{
    public function testContainerCanSetAndGetDependency(): void
    {
        // 1. On instancie ton conteneur réel
        $container = new Container();

        // 2. On crée un objet anonyme standard pour simuler un service
        $myService = new \stdClass();
        $myService->foo = 'bar';

        // 3. On enregistre ce service dans ton container
        $container->set('test.service', $myService);

        // 4. On le récupère
        $resolvedService = $container->get('test.service');

        // 5. Assertion : On vérifie mathématiquement que c'est le même objet
        $this->assertSame($myService, $resolvedService);
    }

    public function testContainerThrowsExceptionWhenServiceNotFound(): void
    {
        $container = new Container();

        // C'est l'exception attendue d'après la logique métier de votre conteneur
        $this->expectException(\App\Core\Exception\NotFoundException::class);

        $container->get('un.service.qui.n.existe.pas');
    }

}
