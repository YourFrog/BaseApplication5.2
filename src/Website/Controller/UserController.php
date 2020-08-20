<?php

namespace YourFrog\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Kontroller obsługujący użytkowników
 *
 * @package YourFrog\Website\Controller
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     *  Główny ekran użytkownika po zalogowaniu
     *
     * @Route("/dashboard", name="website.user.dashboard")
     *
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('website/user/dashboard.html.twig');
    }

    /**
     * @Route("/logout", name="website.user.logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}