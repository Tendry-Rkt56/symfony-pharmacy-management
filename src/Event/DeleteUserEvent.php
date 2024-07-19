<?php

namespace App\Event;

use App\Entity\User;

class DeleteUserEvent
{

     public function __construct (public User $user)
     {

     }

}