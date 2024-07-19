<?php

namespace App\Controller;

use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class APIController extends AbstractController
{

     #[Route('/API/medicament', name: 'API.medicament', methods:['GET'])]
     public function medicament (Request $request, MedicamentRepository $repository)
     {
          $query = $request->query->get('value');
          $medicaments = $repository->getWithSearch($query);
          return $this->json($medicaments, 200, [], [
               'groups' => ['medicament.index'],
          ]);
     }

}