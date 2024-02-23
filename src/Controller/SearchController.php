<?php

namespace App\Controller;

use App\Api\ProductsQuery;
use App\Repository\Api\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_search')]
class SearchController extends AbstractController
{
    public function __construct(
        readonly private ProductsRepository $productsRepository,
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

        $results = $this->productsRepository->searchProducts($productsQuery);

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
