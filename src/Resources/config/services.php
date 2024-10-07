<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Kurozumi\GithubWebhookBundle\Webhook\Client\GithubRequestParser;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('webhook.request_parser.github', GithubRequestParser::class)
        ->alias(GithubRequestParser::class, 'webhook.request_parser.github');
};
