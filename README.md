# GitHub Webhook Bundle

## Installation

```shell
composer require kurozumi/github-webhook-bundle
```
The configuration:

```env
# .env

WEBHOOK_GITHUB_SECRET=1z9Y48dbgqxZi...
```

## Webhook

Create a route:

```yaml
framework:
    webhook:
        routing:
          github:
              service: webhook.request_parser.github
              secret: '%env(WEBHOOK_GITHUB_SECRET)%'
```

And a consume:

```php
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer(name: 'github')]
final class GithubConsumer implements ConsumerInterface
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

```

## How to use

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface
use Symfony\Component\RemoteEvent\RemoteEvent;

class GithubWebhookListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'pull_request' => 'onPullRequest',
        ]
    }
    
    public function onPullRequest(RemoteEvent $event): void
    {
        $payload = $event->getPayload();
        
        // do something
    }
}
```

## Webhook events and payloads

https://docs.github.com/en/webhooks/webhook-events-and-payloads