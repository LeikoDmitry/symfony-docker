<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookCategoryControllerTest extends WebTestCase
{

	public function testIndex(): void
	{
        $client = static::createClient();
        $client->request(method: 'GET', uri: '/api/v1/categories');
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
            expectedFile: __DIR__.'/responses/BookCategoryControllerTest_testIndex.json',
            actualJson: $response
        );
	}
}
