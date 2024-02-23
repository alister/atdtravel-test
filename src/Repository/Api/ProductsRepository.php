<?php

namespace App\Repository\Api;

use App\Api\ProductsQuery;
use App\Model\ApiProductsCollection;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ProductsRepository
{
    public function __construct(
        private HttpClientInterface $atProductsClient,
        readonly private SerializerInterface $serializer,
        readonly private CacheInterface $cachePool,
    ) { }

    public function searchProducts(ProductsQuery $productsQuery): ApiProductsCollection
    {
        // The callable will only be executed on a cache miss.
        return $this->cachePool->get($productsQuery->cacheKey(), function (ItemInterface $item) use ($productsQuery): ApiProductsCollection {
            $item->expiresAfter(3600);

            $response = $this->atProductsClient->request('GET', '', $productsQuery->toHttpOptions()->toArray());

            $statusCode = $response->getStatusCode();
            if ($statusCode === 404) {
                return ApiProductsCollection::createEmpty();
            }

            $content = $response->getContent();

            return $this->serializer->deserialize($content, ApiProductsCollection::class, 'json');
        });
    }
}
