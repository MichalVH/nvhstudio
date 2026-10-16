<?php

declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerInterface;
use App\Core\Exception\ContainerException;
use App\Core\Exception\NotFoundException;
use ReflectionClass;
use ReflectionNamedType;

final class Container implements ContainerInterface
{
    /** @var array<string, callable|object> */
    private array $entries = [];
    
    /** @var array<string, object> */
    private array $instances = [];


    public function set(string $id, callable|object $concrete): self
    {
        $this->entries[$id] = $concrete;
        // CORRECTIF : Élimine le bug de désynchronisation en purgeant le cache d'instance
        unset($this->instances[$id]);
        return $this;
    }

    public function get(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!$this->has($id) && !class_exists($id)) {
            throw new NotFoundException(sprintf('Le service ou la classe "%s" est introuvable.', $id));
        }

        $instance = $this->resolve($id);
        $this->instances[$id] = $instance;

        return $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]) || class_exists($id);
    }

    private function resolve(string $id): object
    {
        if (isset($this->entries[$id]) && is_callable($this->entries[$id])) {
            return ($this->entries[$id])($this);
        }

        if (isset($this->entries[$id]) && is_object($this->entries[$id])) {
            return $this->entries[$id];
        }

        if (!class_exists($id)) {
            throw new ContainerException(sprintf('Impossible de résoudre "%s" car la classe n\'existe pas.', $id));
        }

        $reflectionClass = new ReflectionClass($id);


        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException(sprintf('La classe "%s" n\'est pas instanciable.', $id));
        }

        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException(sprintf(
                    'Impossible d\'autowirer le paramètre "$%s" dans "%s".',
                    $parameter->getName(),
                    $id
                ));
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
