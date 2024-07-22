<?php

namespace App\EventListener;

use App\Event\VenteDeleteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class VenteDeleteListener
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[AsEventListener(event: VenteDeleteEvent::class)]
    public function onVenteDeleteEvent(VenteDeleteEvent $event): void
    {
        
    }
}
