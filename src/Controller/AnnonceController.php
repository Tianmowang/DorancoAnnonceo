<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonce")
     */
    public function index(AnnonceRepository $ar)
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $ar->findAll(),
        ]);
    }

    /**
     * @Route("/annonce", name="annonce_profil")
     */
    public function profil(AnnonceRepository $ar)
    {
        return $this->render('annonce/profil.html.twig');
    }
    
    /**
     * @Route("/annonce/ajouter", name="annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategorieRepository $cr): Response
    {
        $annonce = new Annonce();
        /* $choices = $cr->findAll();
        $categories = [];
        foreach ($choices as $choice => $name) {
            $categories[] = '\''+"$name"+' => '+$choice+',\'';
        } */
        $form = $this->createForm(AnnonceType::class, $annonce/* , array('categories' => $categories) */);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setAuteur($this->getUser());
            $annonce->setCreation(new DateTime());
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/modifier", name="annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(AnnonceType::class, $annonce);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('annonce');
            }
    
            return $this->render('annonce/edit.html.twig', [
                'annonce' => $annonce,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
    
    /**
     * @Route("/{id}", name="annonce_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Annonce $annonce): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($annonce);
                $entityManager->flush();
            }
    
            return $this->redirectToRoute('annonce');
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/annonce/fiche/{id}", name="fiche_annonce", methods={"GET"})
     */
    public function fiche(AnnonceRepository $ar, $id): Response
    {
        $annonce = $ar->findOneBy([ "id" => $id ]);
        if($annonce){
            return $this->render('annonce/fiche.html.twig', [ 'annonce' => $annonce ]);
        }
        else {
            return $this->redirectToRoute("accueil");
        }
    }
}
