<?php

namespace App\Event;

use App\Entity\Vente;

class StoreVenteEvent 
{

     public function __construct(public Vente $vente, public array $data = [])
     {
          
     }

}