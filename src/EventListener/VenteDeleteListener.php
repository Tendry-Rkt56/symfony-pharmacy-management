<?php

namespace App\EventListener;

use App\Event\VenteDeleteEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class VenteDeleteListener
{
    #[AsEventListener(event: VenteDeleteEvent::class)]
    public function onVenteDeleteEvent(VenteDeleteEvent $event): void
    {
        // ...
    }
}
