<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Si l'utilisateur est connecté, rediriger vers le tableau de bord approprié
        if ($this->getUser()) {
            return $this->redirectToRoute('app_parcours');
        }
        
        // Sinon, rediriger vers la page de login
        return $this->redirectToRoute('app_login');
    }
    
    #[Route('/parcours', name: 'app_parcours')]
    #[IsGranted('ROLE_USER')]
    public function parcours(): Response
    {
        $user = $this->getUser();
        $parcours = $user->getParcours();
        
        return $this->render('parcours/index.html.twig', [
            'parcours' => $parcours,
        ]);
    }
}