<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/hello/{name}", name="hello")
     */
    public function hello($name)
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'name' => $name
        ]);
    }
    /**
     * @Route("/simplicity")
     */
    public function simple()
    {
        return new Response('Просто! Легко! Прекрасно!');
    }
}
