<?php

namespace App\Model;

readonly class BookListRecommendationResponse
{
    /**
     * @param RecommendedBook[] $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return RecommendedBook[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
