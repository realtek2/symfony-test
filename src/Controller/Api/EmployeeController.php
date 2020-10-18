<?php

namespace App\Controller\Api;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("api/employees", name="api-employees")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $employees = $paginator->paginate(
            $employees, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->json($employees);
    }

    /**
    * @Route("api/employee/create", name="api-create-employee")
    */
    public function create(Request $request)
    {
        try {
            $employee = new Employee();
            $form = $this->createForm(EmployeeType::class, $employee);

            $request = $this->transformJsonBody($request);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($employee);
                $em->flush();
            }
            $data = [
                'status' => 201,
                'success' => "You have been successfully created employee! Congratulations",
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
    * @Route("api/employee/update/{id}", name="api-update-employee")
    */
    public function update(Request $request, $id)
    {
        try {
            $employee = new Employee();
            $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

            
            if (!$employee) {
                $data = [
                    'status' => 404,
                    'errors' => "Employee not found",
                ];
                return $this->json($data, 404);
            }

            $request = $this->transformJsonBody($request);
            $form = $this->createForm(EmployeeType::class, $employee);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
            $data = [
                'status' => 201,
                'errors' => "success', 'You have been successfully updated employee! Congratulations",
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
    * @Route("api/employee/{id}", name="api-show-employee")
    */
    public function show($id)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

        if (!$employee) {
            $data = [
                'status' => 404,
                'errors' => "Employee not found",
            ];
            return $this->json($data, 404);
        }

        return $this->json($employee);
    }

    /**
    * @Route("api/employee/delete/{id}", name="api-delete-employee")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);
        
        if (!$employee) {
            $data = [
                'status' => 404,
                'errors' => "employee not found",
            ];
            return $this->json($data, 404);
        }


        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        $data = [
            'status' => 201,
            'errors' => "Employee deleted successfully",
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
