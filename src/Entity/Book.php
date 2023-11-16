<?php

namespace App\Entity;

use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 125)]
    private ?string $slug = null;

    #[ORM\Column(length: 125)]
    private ?string $image = null;

    /** @var array<string> */
    #[ORM\Column(type: 'simple_array')]
    private array $authors = [];

    #[ORM\Column(type: 'date')]
    private DateTimeInterface $publicationDate;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $meap;

    /**
     * @var Collection<BookCategory>
     */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return Collection<BookCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function isMeap(): bool
    {
        return $this->meap;
    }

    public function setMeap(bool $meap): static
    {
        $this->meap = $meap;

        return $this;
    }

    public function getPublicationDate(): DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param array<string> $authors
     */
    public function setAuthors(array $authors): static
    {
        $this->authors = $authors;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
