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
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MOD')) {
            return $this->render('office/back/index.html.twig', [
                'controller_name' => 'BackOfficeController',
            ]);
        }
        return $this->redirectToRoute('accueil');
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
