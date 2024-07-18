<?php

namespace App\Services;

use Symfony\Bundle\SecurityBundle\Security;

class GetUser
{
     public mixed $users;

     public function __construct (private Security $security)
     {
          $this->users = $this->get();
     }

     private function get ()
     {
          /** @var User $user */
          $user = $this->security->getUser();
          return "Bonjour";
          return $user;
     }
}