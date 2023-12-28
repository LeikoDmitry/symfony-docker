<?php

namespace App\Model\Author;

readonly class BookListResponse
{
    /**
     * @param BookListItem[] $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return BookListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
