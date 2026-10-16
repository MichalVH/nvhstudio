<?php

declare(strict_types=1);

use App\Core\Container;
use App\Core\Http\Session;
use App\Services\SecurityService;
use App\Services\MailerService;
use App\Views\Renderer;

/**
 * Enregistrement manuel des dépendances complexes du Container de NVHFAST.
 */
return function (Container $container): void {
    
    // 1. Enregistrement de la Session
    $container->set(Session::class, function (Container $c) {
        return new Session();
    });

    // 2. Enregistrement du Service de Sécurité
    $container->set(SecurityService::class, function (Container $c) {
        /** @var Session $session */
        $session = $c->get(Session::class);
        return new SecurityService($session);
    });

    // 3. Enregistrement du Renderer + Injections globales Twig
    $container->set(Renderer::class, function (Container $c) {
        $renderer = new Renderer();
        
        /** @var SecurityService $securityService */
        $securityService = $c->get(SecurityService::class);
        
        /** @var Session $session */
        $session = $c->get(Session::class);
        
        $reflection = new \ReflectionClass($renderer);
        if ($reflection->hasProperty('twig')) {
            $property = $reflection->getProperty('twig');
            $property->setAccessible(true);
            /** @var \Twig\Environment $twig */
            $twig = $property->getValue($renderer);
            
            // Jeton CSRF
            $twig->addFunction(new \Twig\TwigFunction('csrf_token', function () use ($securityService) {
                return $securityService->generateToken();
            }));

            // MESSAGES FLASH (DÉFINITIF)
            $twig->addFunction(new \Twig\TwigFunction('has_flash', function (string $type) use ($session) {
                return $session->hasFlash($type);
            }));

            $twig->addFunction(new \Twig\TwigFunction('get_flash', function (string $type) use ($session) {
                return $session->getFlash($type);
            }));
        }

        return $renderer;
    });

        // Configuration native et sécurisée de la brique de persistance PDO
    $container->set(\App\Core\Database::class, function (Container $c) {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $dbname = $_ENV['DB_NAME'] ?? 'nvhfast_db';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';

        return new \App\Core\Database(
            is_string($host) ? $host : '127.0.0.1',
            is_string($dbname) ? $dbname : 'nvhfast_db',
            is_string($username) ? $username : 'root',
            is_string($password) ? $password : ''
        );
    });

    // Déclaration personnalisée du MailerService au sein du Container
    $container->set(MailerService::class, function () {
        $host = $_ENV['MAIL_HOST'] ?? 'localhost';
        $portValue = $_ENV['MAIL_PORT'] ?? 2525;
        $username = $_ENV['MAIL_USERNAME'] ?? null;
        $password = $_ENV['MAIL_PASSWORD'] ?? null;
        $encryption = $_ENV['MAIL_ENCRYPTION'] ?? 'tls';
        $fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@nvhstudio.com';
        $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'NVHFAST Core';

        return new MailerService([
            'host'         => is_string($host) ? $host : 'localhost',
            'port'         => is_numeric($portValue) ? (int)$portValue : 2525,
            'username'     => is_string($username) ? $username : null,
            'password'     => is_string($password) ? $password : null,
            'encryption'   => is_string($encryption) ? $encryption : 'tls',
            'from_address' => is_string($fromAddress) ? $fromAddress : 'noreply@nvhstudio.com',
            'from_name'    => is_string($fromName) ? $fromName : 'NVHFAST Core',
        ]);
    });
};
