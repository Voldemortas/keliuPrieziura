<?php

namespace App\Controller;

use App\Entity\Cipher;
use App\Entity\Metric;
use App\Entity\RoadType;
use App\Form\CipherType;
use App\Repository\CipherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AdminService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @Route("/cipher")
 */
class CipherController extends AbstractController
{
    private $adminService;
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    private function makeForm(Cipher $cipher, Request $request): Form
    {
        $form = $this->createFormBuilder($cipher)
            ->add('cipher', TextType::class, ['label' => 'Šifras'])
            ->add('name', TextType::class, ['label' => 'Pavadinimas'])
            ->add('metric', EntityType::class, [
                'class' => Metric::class,
                'choice_label' => 'name',
                'label' => 'Matas'
            ])
            ->add('type', EntityType::class, [
                'class' => RoadType::class,
                'choice_label' => 'name',
                'label' => 'Danga',
                'required' => false,
                'empty_data' => ''
            ])
            ->getForm();
        $form->handleRequest($request);
        return $form;
    }


    /**
     * @Route("/", name="cipher_index", methods={"GET"})
     */
    public function index(CipherRepository $cipherRepository): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        return $this->render('cipher/index.html.twig', [
            'ciphers' => $cipherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cipher_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->adminService->isAdmin()) {

            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $cipher = new Cipher();
        $form = $this->makeForm($cipher, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cipher);
            $entityManager->flush();

            return $this->redirectToRoute('cipher_index');
        }

        return $this->render('cipher/new.html.twig', [
            'cipher' => $cipher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cipher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cipher $cipher): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        $form = $this->makeForm($cipher, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cipher_index');
        }

        return $this->render('cipher/edit.html.twig', [
            'cipher' => $cipher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cipher_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cipher $cipher): Response
    {
        if (!$this->adminService->isAdmin()) {
            $response =  $this->redirect('/', 301);
            $response->setCache(['max_age' => 0]);
            return $response;
        }
        if ($this->isCsrfTokenValid('delete' . $cipher->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cipher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cipher_index');
    }
}
