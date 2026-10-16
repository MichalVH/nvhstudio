<?php

declare(strict_types=1);

// 1. Initialisation de l'autoloader optimisé de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Http\Request;

// 2. Instanciation du moteur et exécution du cycle HTTP (Entrée -> Sortie)
$app = new App();
$response = $app->handle(Request::createFromGlobals());

// 3. Envoi de la réponse au navigateur
$response->send();
