<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Http\Response;
use App\Views\Renderer;

/**
 * Gestionnaire d'élite pour les pages institutionnelles et réglementaires.
 */
final class PageController
{
    public function __construct(
        private readonly Renderer $renderer
    ) {}

    /**
     * Gère l'affichage de la page des Mentions Légales et RGPD.
     */
    public function legal(): Response
    {
        // 1. On demande au Renderer de compiler le template et ses variables
        $html = $this->renderer->render('legal.html.twig', [
            'page_title' => 'Mentions Légales & Confidentialité'
        ]);

        // 2. CORRECTIF COHÉRENCE : Alignement strict sur la syntaxe fluide de la Response
        $response = new Response();
        return $response->setStatusCode(200)->setContent($html);
    }

}
