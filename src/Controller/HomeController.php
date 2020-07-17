<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(AnnonceRepository $ar)
    {
        $annonces = $ar->findRecent();
        return $this->render('home/index.html.twig', compact('annonces'));
    }
}
