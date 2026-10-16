<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\Response;
use App\Views\Renderer;

/**
 * Point d'entrée principal pour l'affichage des sites vitrines.
 */
final class HomeController
{
    /**
     * Le constructeur utilise l'autowiring pour recevoir le Renderer Twig de manière isolée.
     */
    public function __construct(
        private Renderer $renderer
    ) {}

    /**
     * Gère l'affichage de la page d'accueil du client.
     */
    public function index(): Response
    {
        // 1. On demande au Renderer de compiler le template et ses variables
        $html = $this->renderer->render('home.html.twig', [
            // Vous pourrez passer des variables dynamiques à vos templates Twig ici
        ]);

        // 2. On encapsule le HTML généré dans notre objet Response étanche
        $response = new Response();
        return $response->setStatusCode(200)->setContent($html);
    }
}
