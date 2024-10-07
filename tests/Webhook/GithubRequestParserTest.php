<?php

declare(strict_types=1);

namespace Kurozumi\GithubWebhookBundle\Tests\Webhook;

use Kurozumi\GithubWebhookBundle\Webhook\Client\GithubRequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\RequestParserInterface;
use Symfony\Component\Webhook\Test\AbstractRequestParserTestCase;

class GithubRequestParserTest extends AbstractRequestParserTestCase
{
    /**
     * @dataProvider getPayloads
     * @covers
     */
    public function testParse(string $payload, RemoteEvent $expected)
    {
        parent::testParse($payload, $expected);
    }

    protected function createRequestParser(): RequestParserInterface
    {
        return new GithubRequestParser();
    }

    protected function getSecret(): string
    {
        return 'GITHUB_WEBHOOK_SECRET';
    }

    protected function createRequest(string $payload): Request
    {
        $expectedSignature = \hash_hmac('sha256', $payload, $this->getSecret());

        return Request::create('/', 'POST', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X-Hub-Signature-256' => 'sha256='.$expectedSignature,
            'HTTP_X-GitHub-Event' => 'issues',
            'HTTP_X-GitHub-Delivery' => '72d3162e-cc78-11e3-81ab-4c9367dc0958',
        ], $payload);
    }
}
