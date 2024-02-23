<?php

namespace App\Twig\Components;

use App\Model\Product;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ProductGrid
{
    /**
     * @var array Product[]
     */
    public array $products;

    public string $spanClass = 'col-span-3';    // TailWind class for flexbox 3-up
}
