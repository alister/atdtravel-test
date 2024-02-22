<?php

namespace App\Model;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ApiProductsCollection
{
    public ApiMeta $meta;

    /**
     * @var Product[]
     */
    #[SerializedName('data')]
    public $products;

    public static function createEmpty(): self
    {
        $me = new self;
        $me->meta = ApiMeta::createEmpty();
        $me->products = [];

        return $me;
    }
}
