<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Matiere;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Service\FileUploader;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        // Récupère l'utilisateur courant
        $user = $this->getUser();
        // Crée le formulaire par le biais du FormType associé
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $avatarFile */
            // Récupère les données de 'avatar' venant du formulaire
            $avatarFile = $form->get('avatar')->getData();
            // Si avatarFile existe :
            if ($avatarFile) {
                // Envoie les données au Service FileUploader 
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);
                $entityManager->persist($user);
                $entityManager->flush();
            }
                

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form
        ]);
    }

     /* Attention : Si plusieurs view ont dans le nom de la route un identifiant et se trouve dans le même dossier
    il faut une ajouter une sépération supplémentaire après l'identifiant */
    #[Route('/matiere/{id}/show', name: 'show_matiere')]
    #[IsGranted('ROLE_USER')]
    public function showMatiere(Matiere $matiere): Response 
    {
        return $this->render('user/showMatiere.html.twig', [
            'matiere' => $matiere
        ]);
    }

    #[Route('/categorie/{id}/show', name: 'show_categorie')]
    #[IsGranted('ROLE_USER')]
    public function showCategorie(Categorie $categorie): Response 
    {
        return $this->render('user/showCategorie.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/formation', name: 'app_formation')]
    #[IsGranted('ROLE_USER')]
    public function formationsList(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findBy([], ["intituleFormation" => "ASC"]);
        
        return $this->render('user/formationsList.html.twig', [
            'formations' => $formations
        ]);
    }

    #[Route('/formation/{id}/show', name: 'show_formation')]
    #[IsGranted('ROLE_USER')]
    public function showformation(Formation $formation): Response 
    {
        return $this->render('user/showFormation.html.twig', [
            'formation' => $formation
        ]);
    }
}
