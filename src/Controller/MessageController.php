<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/message/send', name:'send_message')]
    #[IsGranted('ROLE_USER')]
    public function send(Message $message = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$message) {
            $message = new Message();
        }

        // On crée le formulaire
        $form = $this->createForm(MessageType::class, $message);

        // On inspecte la requête du formulaire
        $form->handleRequest($request);
        // Si le formulaire est envoyé et valide :
        if ($form->isSubmitted() && $form->isValid()) {
                // On déclare que l'utilisateur courant est l'expéditeur
                $message->setSender($this->getUser());
                // On récupère les données du formulaire
                $message = $form->getData();
                // On sauvegarde en mémoire la requête pour l'envoi en bdd
                $entityManager->persist($message);
                // On envoie les changements vers la base de données
                $entityManager->flush();

                // On affiche un message d'alerte que le message à bien été envoyé
            $this->addFlash('message', 'Message envoyé avec succès !');
            // On revient à la page des messages
            return $this->redirectToRoute('app_message');
        }

        return $this->render('message/send.html.twig', [
            'formSend' => $form,
            'messageId' => $message->getId()
        ]);
    }

    #[Route('/message/received', name: 'received_message')]
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }

    #[Route('/message/sent', name: 'sent_message')]
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }

    #[Route('/message/received/{id}/read', name: 'read_message')]
    #[IsGranted('ROLE_USER')]
    public function readMessage(Message $message, EntityManagerInterface $entityManager): Response 
    {
        // Si is_read n'est pas set à true :
        if (!$message->isIsRead(true)) {
            // On set en true la colonne is_read, le message est désormais marqué comme lu
            $message->setIsRead(true);
            // On sauvegarde le changement pour l'envoi en bdd
            $entityManager->persist($message);
            // On envoie le changement dans la bdd
            $entityManager->flush();
        } 
        
        return $this->render('message/read.html.twig', [
            'message' => $message
        ]);
    }

    #[Route('/message/received/{id}/delete', name: 'delete_message')]
    #[IsGranted('ROLE_USER')]
    public function deletemessage(Message $message, EntityManagerInterface $entityManager) {
        
        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute('app_message');
    }

}
