<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AdminType;
use App\Form\ModifierType;
use App\Form\PasswordsType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/profil", name="utilisateur")
     */
    public function index()
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->render('profil/profil.html.twig', [
                'controller_name' => 'UtilisateurController',
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
    
    /**
     * @Route("/utilisateur", name="utilisateurs")
     */
    public function utilisateur(UtilisateurRepository $utilisateurRepository)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('utilisateur/index.html.twig', [
                'membres' => $utilisateurRepository->findAll(),
            ]);
        }
        return $this->redirectToRoute('accueil');
    }
    
    /**
     * @Route("/ajouter", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request, Encoder $encoder): Response
    {
        $membre = new Utilisateur();

        if (is_null($this->getUser())) {
            $form = $this->createForm(UtilisateurType::class, $membre);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                // $mdp = $membre->getPassword();
                $mdp = $form->get("password")->getData();
                $mdp = $encoder->encodePassword($membre, $mdp);
                $membre->setPassword($mdp);
                $membre->setInscription(new DateTime());
                $membre->setRoles(["ROLE_USER"]);
                $entityManager->persist($membre);
                $entityManager->flush();

                return $this->redirectToRoute('utilisateur');
            }

            return $this->render('utilisateur/new.html.twig', [
                'membre' => $membre,
                'form' => $form->createView(),
            ]);
        
        } elseif ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(AdminType::class, $membre);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                // $mdp = $membre->getPassword();
                $mdp = $form->get("password")->getData();
                $mdp = $encoder->encodePassword($membre, $mdp);
                $membre->setPassword($mdp);
                $membre->setInscription(new DateTime());
                $entityManager->persist($membre);
                $entityManager->flush();

                return $this->redirectToRoute('utilisateur');
            }

            return $this->render('utilisateur/new.html.twig', [
                'membre' => $membre,
                'form' => $form->createView(),
            ]);
        }
        
        return $this->redirectToRoute('utilisateur');
    }
    
    /**
     * @Route("/{id}/modifier", name="membre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisateur $membre, Encoder $encoder): Response
    {

        $form = $this->createForm(ModifierType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('utilisateur');
        }
        return $this->render('utilisateur/edit.html.twig', [
            'membre' => $membre,
            'type' => 0,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/password", name="membre_edit_mdp", methods={"GET","POST"})
     */
    public function editMdp(Request $request, Utilisateur $membre, Encoder $encoder): Response
    {

        $form = $this->createForm(PasswordsType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($mdp = $form->get("password")->getData()){
                $mdp = $encoder->encodePassword($membre, $mdp);
                $membre->setPassword($mdp);
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('utilisateur');
        }
        return $this->render('utilisateur/edit.html.twig', [
            'membre' => $membre,
            'type' => 1,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="membre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Utilisateur $membre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membre);
            $entityManager->flush();
        }
        
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('app_logout');
    }
    
}
