<?php

namespace App\Controller;

use App\Entity\Road;
use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Form;

/**
 * @Route("/section")
 */
class SectionController extends AbstractController
{
    private $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    private function makeForm(Section $section, Request $request): Form
    {
        $form = $this->createFormBuilder($section)
            ->add('road', EntityType::class, [
                'class' => Road::class,
                'choice_label' => 'selectName',
                'label' => 'Kelias'
            ])
            ->add('start', NumberType::class, ['label' => 'Pradžia'])
            ->add('finish', NumberType::class, ['label' => 'Pabaiga'])
            ->getForm();
        $form->handleRequest($request);
        return $form;
    }

    /**
     * @Route("/", name="section_index", methods={"GET"})
     */
    public function index(SectionRepository $sectionRepository): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        return $this->render('section/index.html.twig', [
            'sections' => $sectionRepository->createQueryBuilder('u')->orderBy('u.road', 'ASC')->getQuery()->getResult()
        ]);
    }

    /**
     * @Route("/new", name="section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $section = new Section();
        $form = $this->makeForm($section, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('section_index');
        }

        return $this->render('section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $form = $this->createForm(SectionType::class, $section);
        $form = $this->makeForm($section, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('section_index');
        }

        return $this->render('section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        if ($this->isCsrfTokenValid('delete' . $section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('section_index');
    }
}
