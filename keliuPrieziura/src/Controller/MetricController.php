<?php

namespace App\Controller;

use App\Entity\Metric;
use App\Form\MetricType;
use App\Repository\MetricRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;

/**
 * @Route("/metric")
 */
class MetricController extends AbstractController
{
    private $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    /**
     * @Route("/", name="metric_index", methods={"GET"})
     */
    public function index(MetricRepository $metricRepository): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        return $this->render('metric/index.html.twig', [
            'metrics' => $metricRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="metric_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
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
     * @Route("/{id}/edit", name="metric_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Metric $metric): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
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
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        if ($this->isCsrfTokenValid('delete' . $metric->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($metric);
            $entityManager->flush();
        }

        return $this->redirectToRoute('metric_index');
    }
}
