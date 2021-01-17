<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdminRepository;

class ToggleController extends AbstractController
{
    /**
     * @Route("/toggle", name="toggle")
     */
    public function home(AdminRepository $adminRepository): Response
    {
        $admin = $adminRepository->findOneBy(['id' => 1]);
        echo json_encode($admin->getToggled());
        $admin->setToggled(!$admin->getToggled());
        echo json_encode($admin->getToggled());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($admin);
        $entityManager->flush();
        $response =  $this->redirect('/cipher', 301);
        $response->setCache(['max_age' => 0]);
        return $response;
    }
}
