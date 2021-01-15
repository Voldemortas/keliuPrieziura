<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
    * @Route("/")
    */
    public function home(): Response
    {
        return new Response('Hello world')
    }
}