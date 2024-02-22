<?php

declare(strict_types=1);

namespace App\Tests\Repository\Api;

use App\Api\ProductsQuery;
use App\Repository\Api\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ProductsRepositoryTest extends KernelTestCase
{
    private SerializerInterface $serializer;
    private ProductsQuery $productsQuery;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = static::getContainer();
        $this->serializer = $container->get(SerializerInterface::class);

        $this->productsQuery = new ProductsQuery(title: 'london', geo: 'en');
    }

    public  function testSimpleFakeFetchPartial(): void
    {
        $productsRepository = new ProductsRepository(
            $this->getMockResponse(__DIR__.'/../../fixtures/londonResponseOk.1.json'),
            $this->serializer
        );
        $results = $productsRepository->searchProducts($this->productsQuery);

        // assert on the metadata
        $this->assertSame($results->meta->totalCount, 50);
        $this->assertSame($results->meta->saleCur, 'GBP');

        $this->assertGreaterThanOrEqual(count($results->products), 1);
        $this->assertSame($results->products[0]->title, 'London Explorer Pass');
    }

    public  function testSimpleFakeFetchNotFound(): void
    {
        $productsRepository = new ProductsRepository(
            $this->getMockResponse(__DIR__.'/../../fixtures/no_products_found.json', 404),
            $this->serializer
        );
        $results = $productsRepository->searchProducts($this->productsQuery);

        // assert on the metadata
        $this->assertEmpty($results->meta->totalCount);
        $this->assertEmpty($results->meta->saleCur);

        $this->assertEmpty($results->products);
    }

    public  function testSimpleFakeFetchFullEnLondon10Items(): void
    {
        $productsRepository = new ProductsRepository(
            $this->getMockResponse(__DIR__.'/../../fixtures/atdtravel.1.json'),
            $this->serializer
        );
        $results = $productsRepository->searchProducts($this->productsQuery);

        // assert on the metadata
        $this->assertSame($results->meta->totalCount, 57);
        $this->assertSame($results->meta->saleCur, 'GBP');

        $this->assertGreaterThanOrEqual(count($results->products), 10);
        $this->assertSame($results->products[0]->title, 'London Explorer Pass');
    }

    public  function testSimpleFakeFetchFullEnLondon19ItemsPage2(): void
    {
        $productsRepository = new ProductsRepository(
            $this->getMockResponse(__DIR__.'/../../fixtures/atdtravel.3-london.limit19offset19.json'),
            $this->serializer
        );
        $results = $productsRepository->searchProducts($this->productsQuery);

        // assert on the metadata
        $this->assertSame($results->meta->count, 19);
        $this->assertSame($results->meta->totalCount, 57);
        $this->assertSame($results->meta->limit, 19);
        $this->assertSame($results->meta->offset, 19);
        $this->assertSame($results->meta->saleCur, 'GBP');

        $this->assertGreaterThanOrEqual(count($results->products), 19);
        $this->assertSame($results->products[0]->title, 'London, Windsor Castle and Hampton Court Palace from London');
    }

    private function getMockResponse(string $jsonPath, int $statusCode = 200): MockHttpClient
    {
        $this->assertFileIsReadable($jsonPath, 'expected fixture file to be readable');
        $json = file_get_contents($jsonPath);

        $mockResponse = new MockResponse(
            $json,
            [
                'http_code' => $statusCode,
                'response_headers' => [
                    'content-type' => 'application/json',
                ]
            ]
        );

        return new MockHttpClient([$mockResponse]);
    }
}
