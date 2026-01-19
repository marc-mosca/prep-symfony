<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED")]
class UserController extends AbstractController
{

    #[Route("/users", name: "user.index")]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

}
