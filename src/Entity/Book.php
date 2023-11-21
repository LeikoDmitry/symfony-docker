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

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'date_immutable')]
    private DateTimeInterface $publicationDate;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $meap;

    /**
     * @var Collection<BookCategory>
     */
    #[ORM\ManyToMany(targetEntity: BookCategory::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookRelationToBookFormat::class)]
    private Collection $formats;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class)]
    private Collection $review;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->formats = new ArrayCollection();
        $this->review = new ArrayCollection();
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

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, BookRelationToBookFormat>
     */
    public function getFormat(): Collection
    {
        return $this->formats;
    }

    public function setFormats(Collection $formats): static
    {
        $this->formats = $formats;

        return $this;
    }

    public function getReview(): Collection
    {
        return $this->review;
    }

    public function setReview(Collection $review): static
    {
        $this->review = $review;

        return $this;
    }
}
