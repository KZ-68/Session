<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        if($this->getUser()) {
            $contact->setNom($this->getUser()->getNom())
                    ->setPrenom($this->getUser()->getPrenom())
                    ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

                $contact = $form->getData();
                $entityManager->persist($contact);
                $entityManager->flush();

                // Envoi d'un email à l'admin du site
                $email = (new Email())
                ->from($contact->getEmail())
                ->to(new Address('admin@exemple.com', 'Admin Site'))
                ->subject($contact->getSujet())
                ->html($contact->getMessage());

                $mailer->send($email);
        

            $this->addFlash('success', 'Votre demande de contact à été envoyé avec succès !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('user/contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
