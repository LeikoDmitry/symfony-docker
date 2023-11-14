<?php

namespace App\Model;

readonly class BookCategoryListItem
{
    public function __construct(
        public int    $id,
        public string $title,
        public string $slug
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
