<?php

declare(strict_types=1);

namespace App\Tests\Repository\Api;

use App\Api\ProductsQuery;
use App\Repository\Api\ProductsRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ProductsRepositoryTest extends TestCase
{
    public  function testSimpleFakeFetch(): void
    {
        $productsQuery = new ProductsQuery(title: 'london', geo: 'en');

        $client = new MockHttpClient([
            new MockResponse(
                $this->londonResponseOk(),
                [
                    'http_code' => 200,
                    'response_headers' => [
                        'content-type' => 'application/json',
                ]
            ]),
        ]);
        $productsRepository = new ProductsRepository($client);

        $results = $productsRepository->searchProducts($productsQuery);
        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(count($results), 0);
    }

    private function londonResponseOk(): string
    {
        return file_get_contents(__DIR__.'/../../fixtures/londonResponseOk.1.json');
    }
}
