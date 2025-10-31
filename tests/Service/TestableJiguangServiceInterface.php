<?php

namespace JiguangPushBundle\Tests\Service;

use HttpClientBundle\Request\RequestInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface TestableJiguangServiceInterface
{
    public function testGetRequestUrl(RequestInterface $request): string;

    public function testGetRequestMethod(RequestInterface $request): string;

    /** @return array<string, mixed>|null */
    public function testGetRequestOptions(RequestInterface $request): ?array;

    /** @return array<string, mixed> */
    public function testFormatResponse(RequestInterface $request, ResponseInterface $response): array;
}
