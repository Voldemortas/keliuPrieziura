<?php

namespace App\Controller;

use App\Entity\Road;
use App\Form\RoadType;
use App\Repository\RoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;

/**
 * @Route("/road")
 */
class RoadController extends AbstractController
{
    private $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    /**
     * @Route("/", name="road_index", methods={"GET"})
     */
    public function index(RoadRepository $roadRepository): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        return $this->render('road/index.html.twig', [
            'roads' => $roadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="road_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $road = new Road();
        $form = $this->createForm(RoadType::class, $road);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($road);
            $entityManager->flush();

            return $this->redirectToRoute('road_index');
        }

        return $this->render('road/new.html.twig', [
            'road' => $road,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="road_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Road $road): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $form = $this->createForm(RoadType::class, $road);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('road_index');
        }

        return $this->render('road/edit.html.twig', [
            'road' => $road,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Road $road): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        if ($this->isCsrfTokenValid('delete' . $road->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($road);
            $entityManager->flush();
        }

        return $this->redirectToRoute('road_index');
    }
}
