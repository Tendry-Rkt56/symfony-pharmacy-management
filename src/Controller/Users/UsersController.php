<?php

namespace App\Controller\Users;

use App\Entity\Category;
use App\Entity\Detail;
use App\Entity\Medicament;
use App\Entity\User;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users', name: 'users.')]
class UsersController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/medicament', name: 'medicament', methods: ['GET'])]
    public function medicaments(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $search = $request->query->get('search') ?? '';
        $category = $request->query->getInt('category', 1000);
        $offset = ($page - 1) * $limit;
        $medicaments = $this->entity->getRepository(Medicament::class)->getAll($offset, $limit, $search, $category);
        $maxPages = ceil($medicaments->count() / $limit);
        return $this->render('users/medicament/index.html.twig', [
            'medicaments' => $medicaments,
            'page' => $page,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'search' => $search,
            'count' => iterator_count($medicaments),
            'counts' => $medicaments->count(),
            'categorySelected' => $category,
            'categories' => $this->entity->getRepository(Category::class)->findAll()
        ]);
    }

    #[Route('/categories', name: 'categories', methods:['GET'])]
    public function categories(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page - 1) * $limit;
        $categories = $this->entity->getRepository(Category::class)->getAll($offset, $limit);
        $maxPages = ceil(count($categories) / $limit);
        return $this->render('users/category/index.html.twig', [
            'categories' => $categories,
            'page' => $page,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'count' => iterator_count($categories),
            'counts' => $categories->count()
        ]);
    }

    #[Route('/listes', name: 'listes', methods:['GET'])]
    public function users(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $search = $request->query->get('search', '');
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page - 1) * $limit;
        $users = $this->entity->getRepository(User::class)->getAll($offset, $limit, $search);
        return $this->render('users/listes/index.html.twig', [
            'users' => $users,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'maxPages' => ceil(count($users) / $limit),
            'count' => $users->count(),
            'iterator' => iterator_count($users),
        ]);
    } 

    #[Route('/ventes', name: 'ventes', methods:['GET'])]
    public function ventes()
    {
        $ventes = $this->entity->getRepository(Vente::class)->findAll();
        return $this->render('users/ventes/index.html.twig', [
            'ventes' => $ventes,
        ]);
    }

    #[Route('/ventes/{id}', name: 'details', methods:['GET'])]
    public function details(Vente $vente)
    {
        $details = $this->entity->getRepository(Detail::class)->findBy(['vente' => $vente]);
        return $this->render('users/ventes/details.html.twig', [
             'details' => $details,
             'vente' => $vente,
        ]);
    }
}
