<?php

declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Application\Shared\Event\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

final readonly class SymfonyEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private SymfonyEventDispatcherInterface $dispatcher
    ) {
    }

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
