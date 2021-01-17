<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Section;
use App\Entity\Road;
use App\Entity\Cipher;
use App\Form\JobType;
use App\Repository\JobRepository;
use App\Repository\RoadRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Form;


/**
 * @Route("/")
 */
class JobController extends AbstractController
{

    private function makeForm(Job $job, Request $request): Form
    {
        $section = $job->getSection();
        $form = $this->createFormBuilder($job)
            ->add('road', EntityType::class, [
                'class' => Road::class,
                'choice_label' => 'selectName',
                'query_builder' => function (EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('u')
                        ->orderBy('LENGTH(u.number), u.number', 'ASC');
                },
                'mapped' => false,
                'data' => $section !== null ? $section->getRoad() : null
            ])
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('u')->orderBy('u.start', 'ASC');
                },
                'choice_label' => 'selectName',
            ])
            ->add('cipher', EntityType::class, [
                'class' => Cipher::class,
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('u')->orderBy('u.cipher', 'ASC');
                },
                'choice_label' => 'selectName',
            ])
            ->add('distance', NumberType::class)
            ->getForm();
        $form->handleRequest($request);
        return $form;
    }


    /**
     * @Route("/", name="job_index", methods={"GET"})
     */
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAll(),
        ]);
    }

    /**
     * @Route("/job/new", name="job_new", methods={"GET","POST"})
     */
    public function new(Request $request, RoadRepository $roadRepository): Response
    {
        $job = new Job();
        $form = $this->makeForm($job, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('job_index');
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
            'sections' => array_map(function (Road $road) {
                return [$road->getId(), array_map(function (Section $section) {
                    return [$section->getId(), $section->getSelectName()];
                }, $road->getSections()->toArray())];
            }, $roadRepository->findAll()),
            'section' => 'null'
        ]);
    }

    /**
     * @Route("/job/{id}", name="job_show", methods={"GET"})
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/job/{id}/edit", name="job_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Job $job, RoadRepository $roadRepository): Response
    {
        $form = $this->makeForm($job, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_index');
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
            'sections' => array_map(function (Road $road) {
                return [$road->getId(), array_map(function (Section $section) {
                    return [$section->getId(), $section->getSelectName()];
                }, $road->getSections()->toArray())];
            }, $roadRepository->findAll()),
            'section' => $job->getSection()->getId()
        ]);
    }

    /**
     * @Route("/job/{id}", name="job_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }
}
