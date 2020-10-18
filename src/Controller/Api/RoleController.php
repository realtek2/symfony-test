<?php

namespace App\Controller\Api;

use App\Entity\Role;
use App\Form\RoleType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    /**
    * @Route("api/roles", name="api-roles")
    */
    public function index()
    {
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();

        return $this->json($roles);
    }

    /**
    * @Route("api/role/create", name="api-create-role")
    */
    public function create(Request $request)
    {
        try {
            $role = new Role();
            $form = $this->createForm(roleType::class, $role);

            $request = $this->transformJsonBody($request);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($role);
                $em->flush();
            }
            $data = [
                'status' => 201,
                'success' => "You have been successfully created role! Congratulations",
            ];

            return $this->json($data);
        } catch (Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];

            return $this->json($data);
        }
    }

    /**
    * @Route("api/role/update/{id}", name="api-update-role")
    */
    public function update(Request $request, $id)
    {
        try {
            $role = new Role();
            $role = $this->getDoctrine()->getRepository(Role::class)->find($id);
    
            if (!$role) {
                $data = [
                    'status' => 404,
                    'errors' => "Role not found",
                ];
                return $this->json($data, 404);
            }

            $request = $this->transformJsonBody($request);
            $form = $this->createForm(roleType::class, $role);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
            $data = [
                'status' => 201,
                'errors' => "success', 'You have been successfully updated role! Congratulations",
            ];
            return $this->json($data);
        } catch (Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->json($data, 422);
        }
    }

    /**
    * @Route("api/role/{id}", name="api-show-role")
    */
    public function show($id)
    {
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);

        if (!$role) {
            $data = [
                'status' => 404,
                'errors' => "Role not found",
            ];
            return $this->json($data, 404);
        }
        return $this->json($role);
    }

    /**
    * @Route("api/role/delete/{id}", name="api-role-delete")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);
        
        if (!$role) {
            $data = [
                'status' => 404,
                'errors' => "Role not found",
            ];
            return $this->json($data, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($role);
        $em->flush();

        $data = [
            'status' => 201,
            'errors' => "Role deleted successfully",
        ];
        return $this->json($data);
    }
    
    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}
