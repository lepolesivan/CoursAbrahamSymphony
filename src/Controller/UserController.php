<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $user = [
            'nom' => 'Robert',
            'prenom'=> 'Doe',
            'isLoged' => true
        ];

        $users = ["Ivan","Ayoub", "Radouane", "Khaoula", "Abdelkarim", 'Allaan', "Fatima", "Sarah", "Julien", "Abdou", "Mathis"];

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'users' => $users
        ]);
    }
}
