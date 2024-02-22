<?php

namespace App\Repository\Api;

use App\Api\ProductsQuery;
use App\Model\ApiProductsCollection;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ProductsRepository
{
    public function __construct(
        private HttpClientInterface $atProductsClient,
        readonly private SerializerInterface $serializer,
    ) { }

    public function searchProducts(ProductsQuery $productsQuery): ApiProductsCollection
    {
        $response = $this->atProductsClient->request('GET', '', $productsQuery->toHttpOptions()->toArray());

        $statusCode = $response->getStatusCode();
        if ($statusCode === 404) {
            return ApiProductsCollection::createEmpty();
        }

        $content = $response->getContent();

        return $this->serializer->deserialize($content, ApiProductsCollection::class, 'json');
    }
}
