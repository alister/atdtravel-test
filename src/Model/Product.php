<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class Product
{
    public string $id;                  // "213492"
    public string $dest;                // "London",
    public string $title;
    public string $priceFromAdult;      // "63.00",
    public string $priceFromChild;      // "45.00",
    public string $rrpAdult;            // "69.00",
    public string $rrpChild;            // "49.00",

    // $priceFromAll not yet being parsed.
    // public array $priceFromAll; -- see sample fixtures

    public string $seasons;             // ¯\_(ツ)_/¯
    public string $imgSml;              // https://global.atdtravel.com/sites/default/files/imagecache/ ...

    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s'])]
    public \DateTimeImmutable $updated;
}
