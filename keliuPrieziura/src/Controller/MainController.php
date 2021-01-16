<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
    * @Route("/")
    */
    public function home(): Response
    {
        return $this->render('base.html.twig', [
            'body' => 'Hello world',
        ]);
    }
}