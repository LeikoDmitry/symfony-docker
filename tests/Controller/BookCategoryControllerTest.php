<?php

namespace App\Tests\Controller;

use App\Entity\BookCategory;
use App\Tests\AbstractControllerTestCase;

class BookCategoryControllerTest extends AbstractControllerTestCase
{
    public function testIndex(): void
    {
        $this->entityManager->persist((new BookCategory())->setTitle('Test')->setSlug('test'));
        $this->entityManager->flush();

        $this->kernelBrowser->request(method: 'GET', uri: '/api/v1/categories');
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
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
