<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    private $entityManager;
    private $roleRepository;

    public function __construct(EntityManagerInterface $entityManager, RoleRepository $roleRepository)
    {
        $this->entityManager = $entityManager;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @Route("/roles", name="role_index", methods={"GET"})
     */
    public function index(): Response
    {
        $roles = $this->roleRepository->findAll();

        return $this->render('role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("/roles/new", name="role_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($role);
            $this->entityManager->flush();

            $this->addFlash('success', 'Rôle ajouté avec succès.');

            return $this->redirectToRoute('role_index');
        }

        return $this->render('role/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/roles/{id}/edit", name="role_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Role $role): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Rôle modifié avec succès.');

            return $this->redirectToRoute('role_index');
        }

        return $this->render('role/edit.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/roles/{id}", name="role_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Role $role): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($role);
            $this->entityManager->flush();

            $this->addFlash('success', 'Rôle supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('role_index');
    }
}