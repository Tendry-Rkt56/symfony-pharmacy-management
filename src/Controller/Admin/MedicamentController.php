<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Medicament;
use App\Form\MedicamentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin.')]
#[IsGranted('ROLE_ADMIN')]
class MedicamentController extends AbstractController
{

    public function __construct (private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/medicament', name: 'medicament', methods: ['GET'])]
    public function index(Request $request, Security $security): Response
    {
        // dd($security->getUser()->getEmail());
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $search = $request->query->get('search') ?? '';
        $category = $request->query->getInt('category', 1000);
        $offset = ($page - 1) * $limit;
        $medicaments = $this->entity->getRepository(Medicament::class)->getAll($offset, $limit, $search, $category);
        $maxPages = ceil(count($medicaments) / $limit);
        return $this->render('admin/medicament/index.html.twig', [
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

    #[Route('/medicament/new', name: 'medicament.create', methods: ['GET', 'POST'])]
    public function create(Request $request)
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament, [
            'attr' => [
                'class' => 'forms-create container d-flex align-items-center justify-content-center flex-column'
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medicament->setOrdonnance($request->request->get('ordonnance'));
            $this->entity->persist($medicament);
            $this->entity->flush();
            $this->addFlash('success', 'Nouveau médicament crée');
            return $this->redirectToRoute('admin.medicament');
        }
        return $this->render('admin/medicament/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/medicament/{id}', name: 'medicament.edit', methods:['GET', 'POST'])]
    public function edit (Request $request, Medicament $medicament)
    {
        $form = $this->createForm(MedicamentType::class, $medicament, [
            'attr' => [
                'class' => 'forms-create container d-flex align-items-center justify-content-center flex-column'
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medicament->setOrdonnance($request->request->get('ordonnance'));
            $this->entity->flush();
            $this->addFlash('success', "Médicament N°".$medicament->getId()." mis à jour");
            return $this->redirectToRoute('admin.medicament');
        }
        return $this->render('admin/medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/medicament/delete/{id}', name: 'medicament.delete', methods: ['POST'])]
    public function delete (Medicament $medicament)
    {
        $this->entity->remove($medicament);
        $this->entity->flush();
        $this->addFlash("danger", "Médicament supprimé");
        return $this->redirectToRoute('admin.medicament');
    }
}
