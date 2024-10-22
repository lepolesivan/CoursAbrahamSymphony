<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserSecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/new_user', name: 'app_new_user')]
    public function register(EntityManagerInterface $em, UserPasswordHasherInterface $hash)
    {
        $user = new User();
        $user->setEmail('use@mail.com')->setRoles(['ROLE_ADMIN'])->setPassword($hash->hashPassword($user, '1234'));

        $em->persist($user);
        $em->flush();

        return new Response('Utilisateur créer');
    }

    #[Route(path: '/denie', name: 'app_denied')]
    public function denie()
    {
        return $this->render('security/denie.html.twig');
    }
}
