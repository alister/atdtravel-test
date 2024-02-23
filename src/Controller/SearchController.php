<?php

namespace App\Controller;

use App\Api\ProductsQuery;
use App\Model\ApiProductsCollection;
use App\Repository\Api\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/', name: 'app_search')]
class SearchController extends AbstractController
{
    public function __construct(
        readonly private ProductsRepository $productsRepository,
        readonly private CacheInterface $cachePool,
    )
    {
    }

    public function __invoke(
        #[MapQueryParameter] ?string $title,
        #[MapQueryParameter] ?string $geo = 'en',
        #[MapQueryParameter] ?int $limit = 10,
        #[MapQueryParameter] ?int $offset = 0,
    ): Response {
        $productsQuery = new ProductsQuery(title: $title ?? 'london', geo: $geo, limit: $limit, offset: $offset);

        // TODO cache results for a matching (all params)

        // The callable will only be executed on a cache miss.
        $results = $this->cachePool->get($productsQuery->cacheKey(), function (ItemInterface $item) use ($productsQuery): ApiProductsCollection {
            $item->expiresAfter(3600);

            return $this->productsRepository->searchProducts($productsQuery);
        });

        # https://htmx.org/docs/#boosting for search (also maintaining query in search) ?
        # Laracast or SymfonyCast Flex box for 1x, then 2x and 3x card display
        # paging based on results.meta count/current, converting to limit & offset

        return $this->render('search_index.html.twig', [
            'results' => $results,
            'query' => $productsQuery,
            'title' => $title,
            'geo' => $title,
            'limit' => $title,
            'offset' => $title,
        ]);
    }
}
