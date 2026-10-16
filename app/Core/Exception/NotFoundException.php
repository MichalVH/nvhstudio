<?php

declare(strict_types=1);

namespace App\Core\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Exception levée lorsqu'un service demandé n'existe pas dans le conteneur.
 */
final class NotFoundException extends \Exception implements NotFoundExceptionInterface {}
