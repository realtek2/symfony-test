<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employees", name="employees")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $employees = $paginator->paginate(
            $employees, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('employee/index.html.twig', [
            'employees' => $employees
            ]);
    }

    /**
    * @Route("/employee/create", name="create_employee")
    */
    public function create(Request $request)
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'You have been successfully created employee! Congratulations');
            return $this->redirectToRoute('employees');
        }
        return $this->render(
            'employee/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/employee/update/{id}", name="update_employee")
    */
    public function update(Request $request, $id)
    {
        $employee = new Employee();
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'You have been successfully updated employee! Congratulations');
            return $this->redirectToRoute('employees');
        }
        return $this->render(
            'employee/update.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/employee/{id}", name="show_employee")
    */
    public function show($id)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

        return $this->render(
            'employee/show.html.twig',
            ['employee' => $employee]
        );
    }

    /**
    * @Route("/employee/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        $response = new Response();
        $response->send();
    }
}
