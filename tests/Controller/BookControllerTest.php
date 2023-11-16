<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Tests\AbstractControllerTestCase;
use DateTime;
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
            ->setMeap(true)
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setAuthors(['lorem'])
            ->setImage('default.png')
            ->setPublicationDate(
                new DateTime('2023-12-12')
            );

        $this->entityManager->persist($book);

        $this->entityManager->flush();

        return $bookCategory->getId();
    }
}
