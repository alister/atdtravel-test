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
}
