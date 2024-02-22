<?php
declare(strict_types=1);

namespace App\Api;

use App\Exceptions\ApiValueException;
use Symfony\Component\HttpClient\HttpOptions;
use TypeError;

class ProductsQuery
{
    private const TITLE_MAX_LENGTH = 250;
    private const LIMIT_DEFAULT = 10;
    private const LIMIT_MAX = 10000;
    private const OFFSET_DEFAULT = 0;

    public ATLocales $geo = ATLocales::EN;

    public string $title = '';

    /**
     * @phpstan-var int<1, 10000>
     */
    public int $limit = self::LIMIT_DEFAULT;

    /**
     * @phpstan-var int<0,max>
     */
    public int $offset = self::LIMIT_DEFAULT;

    public function __construct(string $title, string $geo, ?int $limit = null, ?int $offset = null)
    {
        $title = trim($title);
        $limit ??= self::LIMIT_DEFAULT;
        $offset ??= self::OFFSET_DEFAULT;

        if ($title === '' || strlen($title) > self::TITLE_MAX_LENGTH) {
            throw ApiValueException::createNotValid('title');
        }
        if ($limit <= 0 || $limit >= self::LIMIT_MAX) {
            throw ApiValueException::createNotValid('limit');
        }
        // no specific offset max limit.
        if ($offset < 0) {
            throw ApiValueException::createNotValid('offset');
        }

        $this->title = $title;
        $this->limit = $limit;
        $this->offset = $offset;

        try {
            $this->geo = ATLocales::tryFrom($geo);
        } catch (TypeError) {
            throw ApiValueException::createNotValid('geo');
        }
    }

    public function toHttpOptions(?HttpOptions $httpOptions = null): HttpOptions
    {
        $httpOptions ??= new HttpOptions();

        $queryParams = [
            'geo' => $this->geo->value,
            'title' => $this->title,
        ];

        if ($this->limit !== self::LIMIT_DEFAULT) {
            $queryParams['limit'] = $this->limit;
        }
        if ($this->offset !== self::OFFSET_DEFAULT) {
            $queryParams['offset'] = $this->offset;
        }

        return $httpOptions->setQuery($queryParams);
    }
}
