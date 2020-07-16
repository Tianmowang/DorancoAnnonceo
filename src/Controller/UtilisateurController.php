<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AdminType;
use App\Form\UtilisateurType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }
    
    /**
     * @Route("/ajouter", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request, Encoder $encoder): Response
    {
        $membre = new Utilisateur();

        if ($this->getUser() == null) {
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
        
        } elseif (in_array("ROLE_ADMIN",$this->getUser()->getRoles())) {
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
}
