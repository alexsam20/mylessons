<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RouteNotFoundException extends \LogicException
{
    public function __construct(
        private string $name,
        private array $params = []
    )
    {
        parent::__construct('Route "' . $this->name . '" not found.');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
