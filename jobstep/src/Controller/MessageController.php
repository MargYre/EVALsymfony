<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageTypeForm;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/message')]
#[IsGranted('ROLE_USER')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message')]
    public function index(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $messagesEnvoyes = $user->getMessagesEnvoyes();
        $messagesRecus = $user->getMessagesRecus();
        
        return $this->render('message/index.html.twig', [
            'messages_envoyes' => $messagesEnvoyes,
            'messages_recus' => $messagesRecus,
        ]);
    }
    
    #[Route('/new', name: 'app_message_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $message->setEmetteur($this->getUser());
        $message->setDateHeure(new \DateTime());
        
        $form = $this->createForm(MessageTypeForm::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            
            $this->addFlash('success', 'Message envoyé avec succès');
            return $this->redirectToRoute('app_message');
        }
        
        return $this->render('message/new.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_message_edit')]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est l'émetteur du message
        if ($message->getEmetteur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez modifier que vos propres messages.');
        }
        
        $form = $this->createForm(MessageTypeForm::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Message modifié avec succès');
            return $this->redirectToRoute('app_message');
        }
        
        return $this->render('message/edit.html.twig', [
            'form' => $form,
            'message' => $message,
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est l'émetteur du message
        if ($message->getEmetteur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez supprimer que vos propres messages.');
        }
        
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
            $this->addFlash('success', 'Message supprimé avec succès');
        }
        
        return $this->redirectToRoute('app_message');
    }
}