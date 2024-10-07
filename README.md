# GitHub Webhook Bundle

## Installation

```shell
composer require kurozumi/github-webhook-bundle
```

## Configuration

Create a route:

```yaml
framework:
    webhook:
        routing:
          github:
              service: webhook.request_parser.github
              secret: '%env(WEBHOOK_GITHUB_SECRET)%'
```

The configuration:

```env
# .env.local

WEBHOOK_GITHUB_SECRET=1z9Y48dbgqxZi...
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