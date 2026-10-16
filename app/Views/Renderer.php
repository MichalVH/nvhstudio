<?php

declare(strict_types=1);

namespace App\Views;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Wrapper étanche assurant l'isolation complète du moteur de rendu Twig.
 */
final class Renderer
{
    private Environment $twig;

    public function __construct()
    {
        $paths = __DIR__ . '/../../templates';
        $loader = new FilesystemLoader($paths);

        // Détection stricte de l'environnement de développement
        $isDevelopment = ($_ENV['APP_ENV'] ?? 'development') === 'development';

        $options = [
            // Synchronisation du mode debug sur vos variables d'environnement
            'debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
            
            // En dev : Twig recompile si le fichier change. En prod : vitesse maximale figée.
            'auto_reload' => $isDevelopment,
            
            // Le cache est TOUJOURS actif pour valider l'écriture dans votre dossier var/cache/
            'cache' => __DIR__ . '/../../var/cache',
        ];

        $this->twig = new Environment($loader, $options);

        // CONFIGURATION DES ASSETS UNIVERSELS
        $this->configureGlobalVariables();
    }

    /**
     * Calcule le chemin de base de l'application et l'injecte globalement dans Twig.
     */
    private function configureGlobalVariables(): void
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptNameString = is_string($scriptName) ? $scriptName : '';
        
        $basePath = dirname($scriptNameString);
        $cleanBasePath = str_replace('\\', '/', $basePath);

        if ($cleanBasePath === '' || $cleanBasePath === '/' || $cleanBasePath === '.') {
            $cleanBasePath = '';
        }

        $this->twig->addGlobal('app', [
            'base_path' => $cleanBasePath
        ]);
    }

    /**
     * Compile un template Twig avec ses variables et retourne le code HTML.
     * 
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        try {
            return $this->twig->render($template, $data);
        } catch (\Throwable $e) {
            throw new \RuntimeException("Erreur lors du rendu du template [{$template}] : " . $e->getMessage(), 0, $e);
        }
    }
}
