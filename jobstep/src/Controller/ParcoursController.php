<?php

namespace App\Controller;

use App\Entity\Parcours;
use App\Entity\Etape;
use App\Entity\Rendu;
use App\Form\RenduType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function uploadRendu(
        Request $request, 
        Parcours $parcours, 
        int $etapeId, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response
    {
        // Vérifier l'accès au parcours
        if (!$parcours->getUsers()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce parcours.');
        }
        
        // Récupérer l'étape
        $etape = null;
        foreach ($parcours->getEtapes() as $e) {
            if ($e->getId() === $etapeId) {
                $etape = $e;
                break;
            }
        }
        
        if (!$etape) {
            throw $this->createNotFoundException('Étape non trouvée');
        }
        
        $uploadedFile = $request->files->get('file');
        
        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            
            try {
                $uploadedFile->move(
                    $this->getParameter('rendus_directory'),
                    $newFilename
                );
                
                // Créer le rendu
                $rendu = new Rendu();
                $rendu->setUrl('/uploads/rendus/'.$newFilename);
                $rendu->setDateHeure(new \DateTime());
                $rendu->setUser($this->getUser());
                $rendu->setEtape($etape);
                
                $entityManager->persist($rendu);
                $entityManager->flush();
                
                $this->addFlash('success', 'Rendu uploadé avec succès');
            } catch (FileException $e) {
                $this->addFlash('error', 'Erreur lors de l\'upload du fichier');
            }
        }
        
        return $this->redirectToRoute('app_parcours_show', ['id' => $parcours->getId()]);
    }
}