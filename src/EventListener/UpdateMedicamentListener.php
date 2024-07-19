<?php

namespace App\EventListener;

use App\Entity\Medicament;
use App\Event\UpdateMedicamentEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class UpdateMedicamentListener
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[AsEventListener(event: UpdateMedicamentEvent::class)]
    public function onUpdateMedicamentEvent(UpdateMedicamentEvent $event): void
    {
        foreach($event->data as $key => $value) {
            /** @var Medicament $medicament */
            $medicament = $this->entity->getRepository(Medicament::class)->find($key);
            $medicament->setNombre($medicament->getNombre() - $value);
            $this->entity->flush();
        }
    }
}
