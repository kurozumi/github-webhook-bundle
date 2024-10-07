<?php

declare(strict_types=1);

namespace Kurozumi\GithubWebhookBundle\Webhook\Client;

use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;
use Symfony\Component\Webhook\Exception\InvalidArgumentException;
use Symfony\Component\Webhook\Exception\RejectWebhookException;

final class GithubRequestParser extends AbstractRequestParser
{
    public function __construct(
        private readonly string $algo = 'sha256',
        private readonly string $signatureHeaderName = 'X-Hub-Signature-256',
        private readonly string $eventHeaderName = 'X-GitHub-Event',
        private readonly string $idHeaderName = 'X-GitHub-Delivery',
    ) {
    }

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            new IsJsonRequestMatcher(),
            new MethodRequestMatcher(Request::METHOD_POST),
        ]);
    }

    protected function doParse(Request $request, #[\SensitiveParameter] string $secret): ?RemoteEvent
    {
        $this->validateHeaders($request->headers);

        $this->validateSignature(
            headers: $request->headers,
            body: $request->getContent(),
            secret: $secret
        );

        $content = $request->toArray();

        return new RemoteEvent(
            name: $request->headers->get($this->eventHeaderName),
            id: $request->headers->get($this->idHeaderName),
            payload: $content
        );
    }

    protected function validateHeaders(HeaderBag $headers): void
    {
        foreach ([$this->signatureHeaderName, $this->eventHeaderName, $this->idHeaderName] as $header) {
            if (!$headers->has($header)) {
                throw new RejectWebhookException(406, sprintf('Missing "%s" HTTP request signature header.', $header));
            }
        }
    }

    protected function validateSignature(HeaderBag $headers, string $body, #[\SensitiveParameter] string $secret): void
    {
        if (!$secret) {
            throw new InvalidArgumentException('A non-empty secret is required.');
        }

        $signature = $headers->get($this->signatureHeaderName);
        if (!hash_equals($signature, $this->algo.'='.hash_hmac($this->algo, $body, $secret))) {
            throw new RejectWebhookException(406, 'Signature is wrong.');
        }
    }
}
