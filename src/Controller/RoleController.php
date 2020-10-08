<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    /**
        * @Route("/roles", name="roles")
        */
    public function index()
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();

        return $this->render('role/index.html.twig', ['roles' => $roles]);
    }

    /**
    * @Route("/role/create", name="create_role")
    */
    public function create(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(roleType::class, $role);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'You have been successfully created role! Congratulations');
            return $this->redirectToRoute('roles');
        }
        return $this->render(
            'role/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/role/update/{id}", name="update_role")
    */
    public function update(Request $request, $id)
    {
        $role = new Role();
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);

        $form = $this->createForm(roleType::class, $role);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'You have been successfully updated role! Congratulations');
            return $this->redirectToRoute('roles');
        }
        return $this->render(
            'role/update.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/role/{id}", name="show_role")
    */
    public function show($id)
    {
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);

        return $this->render(
            'role/show.html.twig',
            ['role' => $role]
        );
    }

    /**
    * @Route("/role/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($role);
        $em->flush();

        $response = new Response();
        $response->send();
    }
}
