<?php

namespace Framework\Http;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_GET = [];
        $_POST = [];
    }

    public function testEmpty(): void
    {
        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertNull($request->getParseBody());
    }

    public function testQueryParams(): void
    {
        $_GET = $data = [
            'name' => 'John Doe',
            'age' => 25,
        ];

        $request = new Request();

        self::assertEquals($data, $request->getQueryParams());
        self::assertNull($request->getParseBody());
    }

    public function testParsedBody(): void
    {
        $_POST = $data =  ['title' => 'Title'];

        $request = new Request();

        self::assertEquals([], $request->getQueryParams());
        self::assertEquals($data, $request->getParseBody());
    }
}
