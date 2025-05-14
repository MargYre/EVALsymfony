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
    
    #[Route('/{id}/edit', name: 'app_ressource_edit')]
    #[IsGranted('ROLE_CONSEILLER')]
    public function edit(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RessourceTypeForm::class, $ressource);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Ressource modifiée avec succès');
            return $this->redirectToRoute('app_ressource');
        }
        
        return $this->render('ressource/edit.html.twig', [
            'form' => $form,
            'ressource' => $ressource,
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_ressource_delete', methods: ['POST'])]
    #[IsGranted('ROLE_CONSEILLER')]
    public function delete(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressource);
            $entityManager->flush();
            $this->addFlash('success', 'Ressource supprimée avec succès');
        }
        
        return $this->redirectToRoute('app_ressource');
    }
}