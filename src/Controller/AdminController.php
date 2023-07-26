<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Matiere;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Form\MatiereType;
use App\Form\CategorieType;
use App\Form\FormationType;
use App\Form\ProgrammeType;
use App\Repository\UserRepository;
use App\Repository\MatiereRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    
    
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        
        return $this->render('admin/index.html.twig', []);
    }

    #[Route('/admin/matiere', name: 'app_matiere')]
    #[IsGranted('ROLE_ADMIN')]
    public function matieresList(MatiereRepository $matiereRepository): Response 
    {
        $matieres = $matiereRepository->findBy([], ["denomination" => "ASC"]);
        return $this->render('admin/matieresList.html.twig', [
            'matieres' => $matieres
        ]);
    }

    #[Route('/admin/matiere/new', name: 'new_matiere')]
    #[Route('/admin/matiere/{id}/edit', name: 'edit_matiere')]
    #[IsGranted('ROLE_ADMIN')]
    public function new_edit_matiere(Matiere $matiere = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$matiere) {
            $matiere = new Matiere();
        }

        $form = $this->createForm(MatiereType::class, $matiere);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $matiere = $form->getData();
            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('app_matiere');
        }

        return $this->render('admin/new_matiere.html.twig', [
            'formAddMatiere' => $form,
            'edit' => $matiere->getId()
        ]);
    }

    #[Route('/admin/matiere/{id}/delete', name: 'delete_matiere')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteMatiere(Matiere $matiere, EntityManagerInterface $entityManager) {
        // Prépare la suppression d'une instance de l'objet 
        $entityManager->remove($matiere);
        // Exécute la suppression
        $entityManager->flush();

        return $this->redirectToRoute('app_matiere');
    }

    #[Route('/admin/categorie', name: 'app_categorie')]
    #[IsGranted('ROLE_ADMIN')]
    public function categoriesList(CategorieRepository $categorieRepository): Response 
    {
        $categories = $categorieRepository->findBy([], ["titre" => "ASC"]);
        return $this->render('admin/categoriesList.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/categorie/new', name: 'new_categorie')]
    #[Route('/admin/categorie/{id}/edit', name: 'edit_categorie')]
    #[IsGranted('ROLE_ADMIN')]
    public function new_edit_categorie(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('admin/new_categorie.html.twig', [
            'formAddCategorie' => $form,
            'edit' => $categorie->getId()
        ]);
    }

    #[Route('/admin/categorie/{id}/delete', name: 'delete_categorie')]
    #[IsGranted('ROLE_ADMIN')]
    public function deletecategorie(Categorie $categorie, EntityManagerInterface $entityManager) {
        // Prépare la suppression d'une instance de l'objet 
        $entityManager->remove($categorie);
        // Exécute la suppression
        $entityManager->flush();

        return $this->redirectToRoute('app_categorie');
    }

    #[Route('/admin/programme', name: 'app_programme')]
    #[IsGranted('ROLE_ADMIN')]
    public function programmesList(ProgrammeRepository $programmeRepository): Response 
    {
        $programmes = $programmeRepository->findBy([], ["duree" => "ASC"]);
        return $this->render('admin/programmesList.html.twig', [
            'programmes' => $programmes
        ]);
    }

    #[Route('/admin/programme/new', name: 'new_programme')]
    #[Route('/admin/programme/{id}/edit', name: 'edit_programme')]
    #[IsGranted('ROLE_ADMIN')]
    public function new_edit_programme(Programme $programme = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$programme) {
            $programme = new Programme();
        }

        $form = $this->createForm(ProgrammeType::class, $programme);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $programme = $form->getData();
            $entityManager->persist($programme);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme');
        }

        return $this->render('admin/new_programme.html.twig', [
            'formAddProgramme' => $form,
            'edit' => $programme->getId()
        ]);
    }

    #[Route('/admin/programme/{id}/delete', name: 'delete_programme')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteProgramme(Programme $programme, EntityManagerInterface $entityManager) {
        // Prépare la suppression d'une instance de l'objet 
        $entityManager->remove($programme);
        // Exécute la suppression
        $entityManager->flush();

        return $this->redirectToRoute('app_programme');
    }

    #[Route('/admin/programme/{id}/show', name: 'show_programme_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function showProgramme(Programme $programme): Response 
    {
        return $this->render('admin/showProgramme.html.twig', [
            'programme' => $programme
        ]);
    }

    #[Route('/admin/formation/new', name: 'new_formation')]
    #[Route('/admin/formation/{id}/edit', name: 'edit_formation')]
    #[IsGranted('ROLE_ADMIN')]
    public function new_edit_formation(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$formation) {
            $formation = new Formation();
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formation = $form->getData();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('admin/new_formation.html.twig', [
            'formAddFormation' => $form,
            'edit' => $formation->getId()
        ]);
    }

    #[Route('/admin/formation/{id}/delete', name: 'delete_formation')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteformation(Formation $formation, EntityManagerInterface $entityManager) {
        // Prépare la suppression d'une instance de l'objet 
        $entityManager->remove($formation);
        // Exécute la suppression
        $entityManager->flush();

        return $this->redirectToRoute('app_formation');
    }

    #[Route('/admin/user', name: 'app_user')]
    public function usersList(UserRepository $userRepository): Response {

        $users = $userRepository->findBy([], ["nom" => "ASC"]);
        
        return $this->render('admin/usersList.html.twig', [
            'users' => $users
        ]);
    }
    
    #[Route('/admin/user/{id}/delete', name: 'delete_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteuser(User $user, UserRepository $userRepository) {
        
        $userRepository->removeUser($user->getId());

        return $this->redirectToRoute('app_user');
    }
}
