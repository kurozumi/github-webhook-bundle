<?php

declare(strict_types=1);

namespace Kurozumi\GithubWebhookBundle\RemoteEvent\Consumer;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer(name: 'github')]
final readonly class GithubWebhookConsumer implements ConsumerInterface
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function consume(RemoteEvent $event): void
    {
        $this->eventDispatcher->dispatch($event, $event->getName());
    }
}
