<?php

namespace App\Controller\Users;

use App\Entity\Medicament;
use App\Entity\Vente;
use App\Event\StoreVenteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users/vente', name: 'users.ventes.')]
class VenteController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entity)
    {
        
    }

    #[Route('/new', name: 'create', methods:['GET'])]
    public function create(): Response
    {
        return $this->render('users/ventes/create.html.twig');
    }

    private function arranegData(array $data = [])
    {
        $ids = [];
        foreach($data as $key => $value) {
            if (str_contains($key, 'medicament')) {
                (int)$id = substr($key, strlen('medicament-'));
                $ids[$id] = (int)$value;
            }
        }
        return $ids;
    }

    private function getTotal(array $data = [])
    {
        $ids = array_keys($data);
        $medicaments = $this->entity->getRepository(Medicament::class)->getMedInVente($ids);
        $total = 0;
        foreach($medicaments as $medicament) {
            $total += $medicament->getPrix() * $data[$medicament->getId()];
        }
        return $total;
    }


    #[Route('/new', name: 'store', methods:['POST'])]
    public function store(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $data = $this->arranegData($request->request->all());
        $total = $this->getTotal($data);
        $vente = new Vente();
        $vente->setTotal($total)
            ->setUser($this->getUser())
            ->setCreatedAt(new \DateTimeImmutable());
        $this->entity->persist($vente);
        $this->entity->flush();
        $dispatcher->dispatch(new StoreVenteEvent($vente, $data));
        return $this->redirectToRoute("users.details", ['id' => $vente->getId()]);
    }
}
