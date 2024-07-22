<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Medicament;
use App\Entity\User;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{

     public function __construct(private EntityManagerInterface $entity)
     {
          
     }

     #[Route('/', name: 'admin.index', methods:['GET'])]
     #[IsGranted('ROLE_ADMIN')]
     public function admin()
     {
          $medicaments = $this->entity->getRepository(Medicament::class)->medicamentNumber();
          $categories = $this->entity->getRepository(Category::class)->categoryNumber();
          $users = $this->entity->getRepository(User::class)->userNumber();
          $lastVentes = $this->entity->getRepository(Vente::class)->getSomme();
          $total = 0;
          foreach($lastVentes as $vente) {
               $total += $vente['total'];
          }
          $ventes = $this->entity->getRepository(Vente::class)->getLastVente();
          return $this->render('admin/home.html.twig', [
               'medicaments' => $medicaments,
               'categories' => $categories,
               'users' => $users,
               'total' => $total,
               'ventes' => $ventes,
          ]);
     }

     #[Route('/users', name: 'users.index', methods:['GET'])]
     #[IsGranted('ROLE_USER')]
     public function user()
     {
          $medicaments = $this->entity->getRepository(Medicament::class)->medicamentNumber();
          $categories = $this->entity->getRepository(Category::class)->categoryNumber();
          $users = $this->entity->getRepository(User::class)->userNumber();
          $lastVentes = $this->entity->getRepository(Vente::class)->getSomme();
          $total = 0;
          foreach($lastVentes as $vente) {
               $total += $vente['total'];
          }
          $ventes = $this->entity->getRepository(Vente::class)->getLastVente();
          return $this->render('users/home.html.twig', [
               'medicaments' => $medicaments,
               'categories' => $categories,
               'users' => $users,
               'total' => $total,
               'ventes' => $ventes,
          ]);
     }

}