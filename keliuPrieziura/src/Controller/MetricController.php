<?php

namespace App\Controller;

use App\Entity\Metric;
use App\Form\MetricType;
use App\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/metric")
 */
class MetricController extends AbstractController
{
    /**
     * @Route("/", name="metric_index", methods={"GET"})
     */
    public function index(MetricRepository $metricRepository): Response
    {
        return $this->render('metric/index.html.twig', [
            'metrics' => $metricRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="metric_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $metric = new Metric();
        $form = $this->createForm(MetricType::class, $metric);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($metric);
            $entityManager->flush();

            return $this->redirectToRoute('metric_index');
        }

        return $this->render('metric/new.html.twig', [
            'metric' => $metric,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="metric_show", methods={"GET"})
     */
    public function show(Metric $metric): Response
    {
        return $this->render('metric/show.html.twig', [
            'metric' => $metric,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="metric_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Metric $metric): Response
    {
        $form = $this->createForm(MetricType::class, $metric);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('metric_index');
        }

        return $this->render('metric/edit.html.twig', [
            'metric' => $metric,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="metric_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Metric $metric): Response
    {
        if ($this->isCsrfTokenValid('delete'.$metric->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($metric);
            $entityManager->flush();
        }

        return $this->redirectToRoute('metric_index');
    }
}