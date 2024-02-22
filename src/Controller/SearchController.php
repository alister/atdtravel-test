<?php

namespace App\Controller;

use App\Repository\Api\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_search')]
class SearchController extends AbstractController
{
    public function __construct(readonly private ProductsRepository $productsRepository)
    {
    }

    public function __invoke(): Response
    {
        // # [MapQueryString] ProductsQuery $productsQuery

        return $this->render('search_index.html.twig', [
            'number' => 4,
        ]);
    }
}
