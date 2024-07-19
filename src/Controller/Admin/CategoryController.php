<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page - 1) * $limit;
        $categories = $this->entity->getRepository(Category::class)->getAll($offset, $limit);
        $maxPages = ceil(count($categories) / $limit);
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'page' => $page,
            'limit' => $limit,
            'maxPages' => $maxPages,
            'count' => iterator_count($categories),
            'counts' => $categories->count()
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create()
    {
        return $this->render('admin/category/create.html.twig');
    }

    #[Route('/create', name: 'store', methods: ['POST'])]
    public function store(Request $request)
    {
        $category = new Category();
        $category->setNom($request->request->get('nom'));
        $this->entity->persist($category);
        $this->entity->flush();
        $this->addFlash('success', "Nouvelle catégorie créee");
        return $this->redirectToRoute('admin.category.index');
    }

    #[Route('/{id}', name:'edit', methods: ['GET'])]
    public function edit (Category $category)
    {
        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['POST'])]
    public function update(Request $request, Category $category)
    {
        $category->setNom($request->request->get('nom'));
        $this->entity->flush();
        $this->addFlash('success', "Catégorie N°".$category->getNom()." mise à jour");
        return $this->redirectToRoute('admin.category.index');
    }

    #[Route('/delete/{id}', name: 'delete', methods:['POST'])]
    public function delete(Category $category)
    {
        $this->entity->remove($category);
        $this->entity->flush();
        $this->addFlash("danger", "Catégorie supprimé");
        return $this->redirectToRoute('admin.category.index');
    }

}
