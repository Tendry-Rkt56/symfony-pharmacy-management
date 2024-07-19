<?php

namespace App\Controller\Admin;

use App\Entity\Detail;
use App\Entity\Vente;
use App\Event\VenteDeleteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/ventes', name: 'admin.ventes.')]
class VenteController extends AbstractController
{

     public function __construct(private EntityManagerInterface $entity)
     {
          
     }

     #[Route('/', name: 'index', methods:['GET'])]
     public function index()
     {
          $ventes = $this->entity->getRepository(Vente::class)->findAll();
          return $this->render('admin/vente/index.html.twig', ['ventes' => $ventes]);
     }

     #[Route('/vente/{id}', name: 'delete', methods:['POST'])]
     public function delete(EventDispatcherInterface $dispatcher, Vente $vente)
     {
          // $dispatcher->dispatch(new VenteDeleteEvent($vente));
          $this->entity->remove($vente);
          $this->entity->flush();
          $this->addFlash("danger", 'Vente supprimée');
          return $this->redirectToRoute('admin.ventes.index');
     }

     #[Route('/{id}', name: 'detail', methods: ['GET'])]
     public function details (Vente $vente)
     {
          $details = $this->entity->getRepository(Detail::class)->findBy(['vente' => $vente]);
          return $this->render('admin/vente/details.html.twig', [
               'details' => $details,
               'vente' => $vente,
          ]);
     }

}