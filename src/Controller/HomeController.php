<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
     #[Route('/home', name: 'app_home')] /*on utilise soit cette méthode comme route ou 
    l'autre dans le fichier config routes.yaml */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Hunter',
        ]);
    }

    #[Route('/gon', name: 'app_Hxh')]
    public function hxh(): Response
    {
        return new Response('Hunter x Hunter est le meilleur');
    }

    #[Route('article/{id}', name: 'single_article', defaults: ['id' => 'touts les articles'])]
    public function showArticle( $id)
    {
        return new Response("L'identifiant est " . $id);
    }
}
