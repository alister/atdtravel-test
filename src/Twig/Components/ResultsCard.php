<?php

namespace App\Twig\Components;

use App\Model\Product;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ResultsCard
{
    public Product $product;
    // public string $dest;
    // public string $title;
    // public string $imgSml;
}
