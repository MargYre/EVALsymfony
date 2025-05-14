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
}