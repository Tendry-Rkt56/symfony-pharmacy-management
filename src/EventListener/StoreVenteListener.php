<?php

namespace App\EventListener;

use App\Entity\Detail;
use App\Entity\Medicament;
use App\Event\StoreVenteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class StoreVenteListener
{
    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[AsEventListener(event: StoreVenteEvent::class)]
    public function onStoreVenteEvent(StoreVenteEvent $event): void
    {  
        foreach($event->data as $key => $value) {
            $details = new Detail();
            $details->setMedicament($this->entity->getRepository(Medicament::class)->find($key))
                 ->setNombre($value)
                 ->setVente($event->vente);
            $this->entity->persist($details);
       }
       $this->entity->flush();
    }
    
}
