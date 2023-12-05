<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;

class AdminControllerTest extends AbstractControllerTestCase
{
    public function testGrantAuthor()
	{
        $username = 'test@gmail.com';
        $password = 'testTest';

        $this->createUser($username, $password, ['ROLE_ADMIN']);

        $user = $this->createUser('user@gmail.com', $password, ['ROLE_USER']);

        $this->auth($username, $password);

        $this->kernelBrowser->request(method: 'POST', uri: '/api/v1/admin/grand-author/'.$user->getId());

        $this->assertResponseIsSuccessful();
    }
}
