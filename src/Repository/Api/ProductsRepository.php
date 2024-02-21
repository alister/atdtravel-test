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

        // dump(StatusCode: $response->getStatusCode());
        // dump(Headers: $response->getHeaders(false));
        $content = $response->getContent();

        return $this->serializer->deserialize($content, ApiProductsCollection::class, 'json');
    }
}
