<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'admin.users.')]
class UserController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $search = $request->query->get('search', '');
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page - 1) * $limit;
        $users = $this->entity->getRepository(User::class)->getAll($offset, $limit, $search);
        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'page' => $page,
            'limit' => $limit,
            'search' => $search,
            'maxPages' => ceil(count($users) / $limit),
            'count' => $users->count(),
            'iterator' => iterator_count($users),
        ]);
    }

    #[Route('/new', name: 'create', methods:['GET'])]
    public function register ()
    {
        return $this->render('admin/user/register.html.twig');
    }

    #[Route('/new', name: 'store', methods:['POST'])]
    public function store (Request $request, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $roles = strtoupper($request->request->get('roles')) == "ADMIN" ? ['ROLE_ADMIN'] : [];
        $user->setNom($request->request->get('nom'))
             ->setPrenom($request->request->get('prenom'))
             ->setEmail($request->request->get('email'))
             ->setRoles($roles)
             ->setPassword($hasher->hashPassword($user, $request->request->get('password')))
             ->setImage($this->checkImage($request->files->get('image'), $user));
        $this->entity->persist($user);
        $this->entity->flush();
        $this->addFlash('success', 'Nouvel utilisateur crée');
        return $this->redirectToRoute('admin.users.index');
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

    #[Route('/edit', name: 'edit', methods: ['GET'])]
    public function edit ()
    {
        return $this->render('admin/user/edit.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'update', methods: ['POST'])]
    public function update (Request $request, UserPasswordHasherInterface $hasher)
    {
        /** @var User $user */
        $user = $this->getUser();
        $roles = strtoupper($request->request->get('roles')) == "ADMIN" ? ['ROLE_ADMIN'] : [];
        $user->setNom($request->request->get('nom'))
             ->setPrenom($request->request->get('prenom'))
             ->setEmail($request->request->get('email'))
             ->setRoles($roles)
             ->setPassword($hasher->hashPassword($user, $request->request->get('password')))
             ->setImage($this->checkImage($request->files->get('image'), $user));
        $this->entity->flush();
        $this->addFlash('success', 'Utilisateur N°'.$user->getId()." mise à jour");
        return $this->redirectToRoute('admin.users.index');
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete (User $user)
    {
        $this->deleteImage($user);
        $this->entity->remove($user);
        $this->entity->flush();
        $this->addFlash('danger', 'Utilisateur supprimé');
        return $this->redirectToRoute('admin.users.index');
    }

    #[Route('/profil/{id}', name: 'profil', methods:['GET'])]
    public function profil (User $user)
    {
        return $this->render('admin/user/profil.html.twig', [
            'user' => $user,
        ]);
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
