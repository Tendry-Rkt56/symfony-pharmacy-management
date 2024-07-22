<?php

namespace App\Controller\Users;

use App\Entity\Category;
use App\Entity\Detail;
use App\Entity\Medicament;
use App\Entity\User;
use App\Entity\Vente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users', name: 'users.')]
#[IsGranted('ROLE_USER')]
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

    //--------------------------------------------------------------------

    #[Route('/profil/{id}', name: 'profil', methods:['GET'])]
    public function profil (User $user)
    {
        return $this->render('users/profil/profil.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'edit', methods: ['GET'])]
    public function edit ()
    {
        return $this->render('users/profil/edit.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    private function checkImage (?UploadedFile $file, User $user)
    {
        if (!$file instanceof UploadedFile && $user->getImage() == null) return null;
        elseif (!$file instanceof UploadedFile && $user->getImage() !== null) return $user->getImage();
        else {
            $this->deleteImage($user);
            $fileName = md5(uniqid("user")).'.'.$file->guessExtension();
            $file->move($this->getParameter('kernel.project_dir').'/public/image/users/',$fileName);
            return $fileName;
        }
    }

    #[Route('/edit', name: 'update', methods: ['POST'])]
    public function update (Request $request, UserPasswordHasherInterface $hasher)
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->setNom($request->request->get('nom'))
             ->setPrenom($request->request->get('prenom'))
             ->setEmail($request->request->get('email'))
             ->setAdresse($request->request->get('adresse'))
             ->setTelephone($request->request->get('telephone'))
             ->setPassword($hasher->hashPassword($user, $request->request->get('password')))
             ->setImage($this->checkImage($request->files->get('image'), $user));
        $this->entity->flush();
        return $this->redirectToRoute('users.profil', ['id' => $user->getId()]);
    }

    private function deleteImage (User $user): void
    {
        if ($user->getImage() !== null) {
            $path = $this->getParameter('kernel.project_dir').'/public/user/'.$user->getImage();
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
