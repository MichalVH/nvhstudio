<?php

declare(strict_types=1);

namespace App\Core\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Exception générique pour toute erreur survenue à l'intérieur du conteneur.
 */
final class ContainerException extends \Exception implements ContainerExceptionInterface {}
