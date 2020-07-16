<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OfficeController extends AbstractController
{
    
    /**
     * @Route("/office", name="office")
     */
    public function index()
    {
        if (in_array('ROLE_ADMIN',$this->getUser()->getRoles()) || in_array('ROLE_MOD', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('back');
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/back-office", name="back")
     */
    public function back()
    {
        return $this->render('office/back/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    /**
     * @Route("/front-office", name="front")
     */
    public function front()
    {
        return $this->render('office/front/index.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }
}
