<?php

namespace App\Repository\Api;

use App\Api\ProductsQuery;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ProductsRepository
{
    public function __construct(private HttpClientInterface $atProductsClient)
    {
    }

    public function searchProducts(ProductsQuery $productsQuery): array
    {
        $response = $this->atProductsClient->request('GET', '', $productsQuery->toHttpOptions()->toArray());

        // dump(StatusCode: $response->getStatusCode());
        // dump(Headers: $response->getHeaders(false));
        // $content = $response->getContent(false);
        // dump(Content: $content);

        // deserialize $content into an array of results

        return [];
    }
}
