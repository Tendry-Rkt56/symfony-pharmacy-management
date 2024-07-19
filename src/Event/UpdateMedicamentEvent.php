<?php

namespace App\Event;

class UpdateMedicamentEvent
{

     public function __construct(public array $data = [])
     {
          
     }

}