<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RequestNotMatchedException extends \LogicException
{
    public function __construct(private ServerRequestInterface $request)
    {
        parent::__construct('Matches not found.');
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
