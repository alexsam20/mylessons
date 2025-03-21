<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class RouteNotFoundException extends \LogicException
{
    public function __construct(
        private string $name,
        private array $params = [],
        Throwable $previous = null
    )
    {
        parent::__construct('Route "' . $this->name . '" not found.', 0, $previous);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
