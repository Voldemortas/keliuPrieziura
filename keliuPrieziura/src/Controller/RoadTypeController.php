<?php

namespace App\Controller;

use App\Entity\RoadType;
use App\Form\RoadTypeType;
use App\Repository\RoadTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;

/**
 * @Route("/road_type")
 */
class RoadTypeController extends AbstractController
{
    private $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    /**
     * @Route("/", name="road_type_index", methods={"GET"})
     */
    public function index(RoadTypeRepository $roadTypeRepository): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        return $this->render('road_type/index.html.twig', [
            'road_types' => $roadTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="road_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $roadType = new RoadType();
        $form = $this->createForm(RoadTypeType::class, $roadType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($roadType);
            $entityManager->flush();

            return $this->redirectToRoute('road_type_index');
        }

        return $this->render('road_type/new.html.twig', [
            'road_type' => $roadType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="road_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RoadType $roadType): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $form = $this->createForm(RoadTypeType::class, $roadType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('road_type_index');
        }

        return $this->render('road_type/edit.html.twig', [
            'road_type' => $roadType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="road_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RoadType $roadType): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        if ($this->isCsrfTokenValid('delete' . $roadType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($roadType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('road_type_index');
    }
}
