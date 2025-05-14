<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Form\RessourceTypeForm;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ressource')]
#[IsGranted('ROLE_USER')]
class RessourceController extends AbstractController
{
    #[Route('/', name: 'app_ressource')]
    public function index(RessourceRepository $ressourceRepository): Response
    {
        $ressources = $ressourceRepository->findAll();
        
        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }
    
    #[Route('/new', name: 'app_ressource_new')]
    #[IsGranted('ROLE_CONSEILLER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(RessourceTypeForm::class, $ressource);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ressource);
            $entityManager->flush();
            
            $this->addFlash('success', 'Ressource créée avec succès');
            return $this->redirectToRoute('app_ressource');
        }
        
        return $this->render('ressource/new.html.twig', [
            'form' => $form,
        ]);
    }
}