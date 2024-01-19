<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTestCase;
use Symfony\Component\HttpFoundation\Response;

class SubscriberControllerTest extends AbstractControllerTestCase
{
    public function testSubscribe(): void
    {
        $this->kernelBrowser->request(
            method: 'POST',
            uri: '/api/v1/subscribes',
            parameters: ['email' => 'test@mail.ru', 'agreed' => true]
        );

        $this->assertResponseIsSuccessful();
    }

    public function testSubscribeNotAgreed(): void
    {
        $this->kernelBrowser->request(
            method: 'POST',
            uri: '/api/v1/subscribes',
            parameters: ['email' => 'test@mail.ru']
        );

        $content = json_decode($this->kernelBrowser->getResponse()->getContent());

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $details = new \stdClass();
        $details->trace = 'This value should be of type unknown.';

        $this->assertJsonDocumentMatches($content, [
            '$.message' => 'This value should be of type unknown.',
            '$.details' => $details
        ]);
    }
}
