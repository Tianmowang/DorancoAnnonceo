<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie")
     */
    public function index(CategorieRepository $categorieRepository)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('categorie/index.html.twig', [
                'categories' => $categorieRepository->findAll(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
    
    /**
     * @Route("/ajouter", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $categorie = new Categorie();
            $form = $this->createForm(CategorieType::class, $categorie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
    
                return $this->redirectToRoute('categorie');
            }
    
            return $this->render('categorie/new.html.twig', [
                'categories' => $categorie,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }

    
    /**
     * @Route("/{id}/modifier", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(CategorieType::class, $categorie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('categorie');
            }
    
            return $this->render('categorie/edit.html.twig', [
                'categorie' => $categorie,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
    
    /**
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($categorie);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('categorie');
        }
        return $this->redirectToRoute('accueil');
    }
}
