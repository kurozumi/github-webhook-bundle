GitHub Webhook Bundle
===============

Webhook
-------

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