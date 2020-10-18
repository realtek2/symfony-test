<?php

namespace App\Controller\Api;

use App\Entity\Company;
use App\Form\CompanyType;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("api/companies", name="api-companies")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();

        $companies = $paginator->paginate(
            $companies, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->json($companies);
    }

    /**
    * @Route("api/company/create", name="api-create-company")
    */
    public function create(Request $request)
    {
        try {
            $company = new Company();
            $form = $this->createForm(CompanyType::class, $company);
    
            $request = $this->transformJsonBody($request);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($company);
                $em->flush();
            }
            $data = [
                'status' => 201,
                'success' => "You have been successfully created company! Congratulations",
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
    * @Route("api/company/update/{id}", name="api-update-company")
    */
    public function update(Request $request, $id)
    {
        try {
            $company = new Company();
            $company = $this->getDoctrine()->getRepository(Company::class)->find($id);

            if (!$company) {
                $data = [
                    'status' => 404,
                    'errors' => "Company not found",
                ];
                return $this->json($data, 404);
            }

            $request = $this->transformJsonBody($request);
            $form = $this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
            $data = [
                'status' => 201,
                'errors' => "success', 'You have been successfully updated company! Congratulations",
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
    * @Route("api/company/{id}", name="api-show-company")
    */
    public function show($id)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);

        if (!$company) {
            $data = [
                'status' => 404,
                'errors' => "Company not found",
            ];
            return $this->json($data, 404);
        }

        return $this->json($company);
    }

    /**
    * @Route("api/company/delete/{id}", name="api-delete-company")
    * @Method({"DELETE"})
    */
    public function delete($id)
    {
        $company = $this->getDoctrine()->getRepository(Company::class)->find($id);
        
        if (!$company) {
            $data = [
                'status' => 404,
                'errors' => "Company not found",
            ];
            return $this->json($data, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        $data = [
            'status' => 201,
            'errors' => "Company deleted successfully",
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
