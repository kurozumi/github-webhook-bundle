<?php

declare(strict_types=1);

namespace Kurozumi\GithubWebhookBundle;

use Kurozumi\GithubWebhookBundle\DependencyInjection\GithubExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GithubWebhookBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new GithubExtension();
    }
}