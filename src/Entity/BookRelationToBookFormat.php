<?php

namespace App\Entity;

use App\Repository\BookRelationToBookFormatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRelationToBookFormatRepository::class)]
class BookRelationToBookFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $price = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $discountPercent = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'formats')]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;

    #[ORM\ManyToOne(targetEntity: BookFormat::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private BookFormat $format;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(int $discountPercent): static
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getFormat(): BookFormat
    {
        return $this->format;
    }

    public function setFormat(BookFormat $format): static
    {
        $this->format = $format;

        return $this;
    }
}
