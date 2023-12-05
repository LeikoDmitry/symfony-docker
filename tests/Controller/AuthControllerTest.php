<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;

class AuthControllerTest extends AbstractControllerTestCase
{
    public function testSignUp()
	{
        $this->kernelBrowser->request(method: 'POST', uri: '/api/v1/auth/signup', parameters:[
            'firstName' => 'Test',
            'lastName' => 'Test',
            'email' => 'test@mail.com',
            'password' => 'testTest1',
            'confirmPassword' => 'testTest1'
        ]);

        $content = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseIsSuccessful();

        $this->assertJsonDocumentMatchesSchema($content, [
            'type' => 'object',
            'required' => ['token', 'refresh_token'],
            'properties' => [
                'token' => ['type' => 'string'],
                'refresh_token' => ['type' => 'string']
            ]
        ]);
    }
}
