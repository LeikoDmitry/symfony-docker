<?php

namespace App\Model\Author;

use App\Model\BookCategoryListItem;
use App\Model\BookFormatListItem;

class BookDetails
{
    public function __construct(
        private int $id,
        private string $title,
        private string $slug,
        private ?string $image,
        /** @var string[] */
        private ?array $authors,
        private ?string $publicationDate,
        private ?string $description,
        /** @var BookCategoryListItem[] */
        private array $categories = [],
        /** @var BookFormatListItem[] */
        private array $formats = [],
    ) {
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param string[] $authors
     */
    public function setAuthors(array $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getPublicationDate(): string
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(string $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return BookCategoryListItem[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param BookCategoryListItem[] $categories
     */
    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return BookFormatListItem[]
     */
    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * @param BookFormatListItem[] $formats
     */
    public function setFormats(array $formats): static
    {
        $this->formats = $formats;

        return $this;
    }
}
