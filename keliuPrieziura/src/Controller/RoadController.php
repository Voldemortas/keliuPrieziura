<?php

namespace App\Controller;

use App\Entity\Road;
use App\Form\RoadType;
use App\Repository\RoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class RoadController extends AbstractController
{
    /**
     * @Route("/", name="road_index", methods={"GET"})
     */
    public function index(RoadRepository $roadRepository): Response
    {
        return $this->render('road/index.html.twig', [
            'roads' => $roadRepository->findAll(),
        ]);
    }

    /**
     * @Route("/job/new", name="road_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
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
     * @Route("/job/{id}", name="road_show", methods={"GET"})
     */
    public function show(Road $road): Response
    {
        return $this->render('road/show.html.twig', [
            'road' => $road,
        ]);
    }

    /**
     * @Route("/job/{id}/edit", name="road_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Road $road): Response
    {
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
     * @Route("/job/{id}", name="road_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Road $road): Response
    {
        if ($this->isCsrfTokenValid('delete' . $road->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($road);
            $entityManager->flush();
        }

        return $this->redirectToRoute('road_index');
    }
}
