<?php

namespace App\Controller\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users/vente', name: 'users.ventes.')]
class VenteController extends AbstractController
{
    #[Route('/new', name: 'create', methods:['GET'])]
    public function create(): Response
    {
        return $this->render('users/ventes/create.html.twig');
    }
}
