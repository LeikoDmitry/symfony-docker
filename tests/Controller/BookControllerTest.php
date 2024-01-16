<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\Review;
use App\Tests\AbstractControllerTestCase;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTestCase
{
    public function testBooksByCategory(): void
    {
        $categoryId = $this->createCategory();
        $this->kernelBrowser->request(method: 'GET', uri: '/api/v1/books/category/'.$categoryId);
        $response = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'authors', 'publicationDate'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'image' => ['type' => 'string'],
                            'authors' => ['type' => 'array', 'items' => ['type' => 'string']],
                            'publicationDate' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Test')->setSlug('test');
        $this->entityManager->persist($bookCategory);

        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setIsbn('111111')
            ->setDescription('Test description')
            ->setPublicationDate(
                new DateTimeImmutable('2023-12-12')
            );

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $bookCategory->getId();
    }

    public function testReview(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([]))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setIsbn('111111')
            ->setDescription('Test description')
            ->setPublicationDate(
                new DateTimeImmutable('2023-12-12')
            );

        $this->entityManager->persist($book);

        $review = (new Review())
            ->setBook($book)
            ->setRating(4)
            ->setContent('test')
            ->setAuthor('test')
            ->setCreatedAt(new DateTimeImmutable('2023-10-10')
            );

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        $this->kernelBrowser->request(method: 'GET', uri: '/api/v1/books/review/'.$book->getId());
        $response = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response, [
            'type' => 'object',
            'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'rating' => ['type' => 'number'],
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'content', 'author', 'rating', 'createdAt'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'rating' => ['type' => 'integer'],
                            'createdAt' => ['type' => 'string'],
                            'author' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testBookById(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([]))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setIsbn('111111')
            ->setDescription('Test description')
            ->setPublicationDate(
                new DateTimeImmutable('2023-12-12')
            );

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->kernelBrowser->request(method: 'GET', uri: '/api/v1/books/'.$book->getId());
        $response = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response, [
            'type' => 'object',
            'required' => ['id', 'title', 'slug', 'image', 'authors', 'publicationDate', 'rating', 'review', 'categories', 'formats'],
            'properties' => [
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'id' => ['type' => 'integer'],
                'image' => ['type' => 'string'],
                'authors' => ['type' => 'array', 'items' => ['type' => 'string']],
                'publicationDate' => ['type' => 'string'],
                'rating' => ['type' => 'number'],
                'review' => ['type' => 'number'],
                'categories' => ['type' => 'array'],
                'formats' => ['type' => 'array'],
            ],
        ]);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testBooksByRecommendations(): void
    {
        $book = (new Book())
            ->setTitle('test')
            ->setSlug('test')
            ->setCategories(new ArrayCollection([]))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setIsbn('111111')
            ->setDescription('Test description')
            ->setPublicationDate(
                new DateTimeImmutable('2023-12-12')
            );

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->kernelBrowser->request(method: 'GET', uri: '/api/v1/books/recommendation/'.$book->getId());
        $response = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($response, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'required' => ['id', 'slug', 'title', 'description'],
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
            ],
        ]);
    }
}
