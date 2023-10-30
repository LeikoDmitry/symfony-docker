<?php

namespace App\Model;

class BookCategoryListItem
{
    public function __construct(
        readonly int $id,
        readonly string $title,
        readonly string $slug
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
