<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="commentaire")
     */
    public function index(CommentaireRepository $cr)
    {
        $commentaires = $cr->findAll();
        return $this->render('commentaire/index.html.twig', compact('commentaires'));
    }

    /**
     * @Route("/ajouter", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $categorie = new Commentaire();
            $form = $this->createForm(CommentaireType::class, $categorie);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($categorie);
                $entityManager->flush();
    
                return $this->redirectToRoute('commentaire');
            }
    
            return $this->render('categorie/new.html.twig', [
                'categories' => $categorie,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
}
