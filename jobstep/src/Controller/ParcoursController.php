<?php

namespace App\Controller;

use App\Entity\Parcours;
use App\Entity\Etape;
use App\Entity\Rendu;
use App\Form\RenduType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/parcours')]
class ParcoursController extends AbstractController
{
    #[Route('/', name: 'app_parcours')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();
        $parcours = $user->getParcours();
        
        return $this->render('parcours/index.html.twig', [
            'parcours' => $parcours,
        ]);
    }
    
    #[Route('/create', name: 'app_parcours_create')]
    #[IsGranted('ROLE_CONSEILLER')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // TODO: Implémenter la création de parcours
        $this->addFlash('info', 'Fonctionnalité à implémenter');
        return $this->redirectToRoute('app_parcours');
    }
    
    #[Route('/{id}', name: 'app_parcours_show')]
    #[IsGranted('ROLE_USER')]
    public function show(Parcours $parcours): Response
    {
        // Vérifier que l'utilisateur a accès à ce parcours
        if (!$parcours->getUsers()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce parcours.');
        }
        
        $etapes = $parcours->getEtapes();
        
        return $this->render('parcours/show.html.twig', [
            'parcours' => $parcours,
            'etapes' => $etapes,
        ]);
    }
    
    #[Route('/{id}/etape/{etapeId}/upload', name: 'app_parcours_upload_rendu', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function uploadRendu(Request $request, Parcours $parcours, int $etapeId, EntityManagerInterface $entityManager): Response
    {
        // TODO: Implémenter l'upload de rendu
        $this->addFlash('success', 'Rendu uploadé avec succès');
        return $this->redirectToRoute('app_parcours_show', ['id' => $parcours->getId()]);
    }
}