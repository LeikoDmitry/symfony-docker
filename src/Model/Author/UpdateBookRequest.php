<?php

namespace App\Model\Author;

class UpdateBookRequest
{
    private ?string $title;
    private ?array $authors = [];
    private ?string $description;
    /** @var BookFormatOptions[]|null */
    private ?array $formats;
    private ?array $categories;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    public function setAuthors(?array $authors): void
    {
        $this->authors = $authors;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getFormats(): ?array
    {
        return $this->formats;
    }

    public function setFormats(?array $formats): void
    {
        $this->formats = $formats;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
    }
}

