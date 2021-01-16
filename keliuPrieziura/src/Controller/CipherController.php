<?php

namespace App\Controller;

use App\Entity\Cipher;
use App\Form\CipherType;
use App\Repository\CipherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cipher")
 */
class CipherController extends AbstractController
{
    /**
     * @Route("/", name="cipher_index", methods={"GET"})
     */
    public function index(CipherRepository $cipherRepository): Response
    {
        return $this->render('cipher/index.html.twig', [
            'ciphers' => $cipherRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cipher_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cipher = new Cipher();
        $form = $this->createForm(CipherType::class, $cipher);
        $form->handleRequest($request);

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
     * @Route("/{id}", name="cipher_show", methods={"GET"})
     */
    public function show(Cipher $cipher): Response
    {
        return $this->render('cipher/show.html.twig', [
            'cipher' => $cipher,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cipher_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cipher $cipher): Response
    {
        $form = $this->createForm(CipherType::class, $cipher);
        $form->handleRequest($request);

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
        if ($this->isCsrfTokenValid('delete'.$cipher->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cipher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cipher_index');
    }
}
