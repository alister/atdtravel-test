<?php

namespace App\Model;

class ApiMeta
{
    public int $count = 0;
    public int $limit = 0;
    public int $offset = 0;
    public string $saleCur = '';
    public int $totalCount = 0;

    public static function createEmpty(): self
    {
        return new self();
    }
}
