<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testBooksByCategory(): void
	{
        $client = static::createClient();
        $client->request(method: 'GET', uri: '/api/v1/books/category/4');
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__.'/responses/BookControllerTest_BookControllerTest.json',
            actualJson: $response
        );
	}
}
