<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Form\PermissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends AbstractController
{
    /**
     * @Route("/permissions", name="permission_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $permissions = $entityManager->getRepository(Permission::class)->findAll();

        return $this->json($permissions, 200, [], ['groups' => 'permission']);
    }

    /**
     * @Route("/permissions", name="permission_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($permission);
            $entityManager->flush();

            return $this->json($permission, 201, [], ['groups' => 'permission']);
        }

        return $this->json($form->getErrors(true, false), 400);
    }

    /**
     * @Route("/permissions/{id}", name="permission_show", methods={"GET"})
     */
    public function show(Permission $permission): Response
    {
        return $this->json($permission, 200, [], ['groups' => 'permission']);
    }

    /**
     * @Route("/permissions/{id}", name="permission_edit", methods={"PUT"})
     */
    public function edit(Request $request, Permission $permission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PermissionType::class, $permission);
        $form->submit($request->request->all(), false); // false to not clear missing fields

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->json($permission, 200, [], ['groups' => 'permission']);
        }

        return $this->json($form->getErrors(true, false), 400);
    }

    /**
     * @Route("/permissions/{id}", name="permission_delete", methods={"DELETE"})
     */
    public function delete(Permission $permission, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($permission);
        $entityManager->flush();

        return new Response(null, 204);
    }
}
