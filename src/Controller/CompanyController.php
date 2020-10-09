<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/companies", name="companies")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();

        $companies = $paginator->paginate(
            $companies, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('company/index.html.twig', [
            'companies' => $companies
            ]);
    }

    /**
    * @Route("/company/create", name="create_company")
    */
    public function create(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'You have been successfully created company! Congratulations');
            return $this->redirectToRoute('companies');
        }
        return $this->render(
            'company/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/company/update/{id}", name="update_company")
    */
    public function update(Request $request, $id)
    {
        $company = new Company();
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'You have been successfully updated company! Congratulations');
            return $this->redirectToRoute('companies');
        }
        return $this->render(
            'company/update.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
    * @Route("/company/{id}", name="show_company")
    */
    public function show($id)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);

        return $this->render(
            'company/show.html.twig',
            ['company' => $company]
        );
    }

    /**
    * @Route("/company/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        $response = new Response();
        $response->send();
    }
}
