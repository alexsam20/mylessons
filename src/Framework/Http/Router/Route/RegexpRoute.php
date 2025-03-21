<?php

namespace Framework\Http\Router\Route;

use Framework\Http\Router\Result;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use function array_filter;
use function array_key_exists;
use function in_array;
use function preg_match;
use function preg_replace_callback;

class RegexpRoute implements Route
{
    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $methods
     * @param array $tokens
     */
    public function __construct(
        public       $name,
        public       $pattern,
        public       $handler,
        public array $methods = [],
        public array $tokens = [],
    ) { }

    /**
     * @param ServerRequestInterface $request
     * @return Result|null
     */
    public function match(ServerRequestInterface $request): ?Result
    {

        if ($this->methods && !in_array($request->getMethod(), $this->methods, true)) {
            return null;
        }
        $pattern = preg_replace_callback('~\{([^\}]+)\}~', function ($matches): string {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return '(?P<' . $argument . '>' . $replace . ')';
        }, $this->pattern);

        $path = $request->getUri()->getPath();

        if (preg_match('~^' . $pattern . '$~i', $path, $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY)
            );
        }
        return null;
    }

    /**
     * @param $name
     * @param array $params
     * @return ?string
     */
    public function generate($name, array $params = []): ?string
    {
        $arguments = array_filter($params);

        if ($name !== $this->name) {
            return null;
        }

        $url = preg_replace_callback('~\{([^\}]+)\}~', static function ($matches) use (&$arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw  new InvalidArgumentException('Missing parameter "' . $argument . '"');
            }
            return $arguments[$argument];
        }, $this->pattern);

        return $url ?? null;
    }
}
