<?php

namespace App\EventListener;

use App\Entity\Vente;
use App\Event\DeleteUserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class DeleteUserListener
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[AsEventListener(event: DeleteUserEvent::class)]
    public function onDeleteUserEvent(DeleteUserEvent $event): void
    {
        $ventes = $this->entity->getRepository(Vente::class)->findBy(['user' => $event->user->getId()]);
        foreach($ventes as $vente) {
            $this->entity->remove($vente);
        }
        $this->entity->flush();
    }
}
