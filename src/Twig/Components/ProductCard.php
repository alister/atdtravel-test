<?php

namespace App\Twig\Components;

use App\Model\Product;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ProductCard
{
    public Product $product;
}
