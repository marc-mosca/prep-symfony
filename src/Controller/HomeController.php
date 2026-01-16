<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    /**
     * Cette route correspond à la racine de l'application ("/"). Elle affiche la page d'accueil en rendant le template
     * Twig `home/index.html.twig`.
     *
     * @return Response Réponse HTTP contenant le rendu de la page d'accueil.
     */
    #[Route("/", name: "home.index")]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

}
