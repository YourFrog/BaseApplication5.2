<?php

namespace YourFrog\Website\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use YourFrog\App\Entity\Security\AccountAuthenticationHistory;

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
     * @Route("/authenticate-history", name="website.user.authenticate-history")
     */
    public function authenticateHistory(): Response
    {
        $params = [];
        $params['items'] = $this->getDoctrine()->getManager()->getRepository(AccountAuthenticationHistory::class)->findAll();

        return $this->render('website/user/authenticate-history.html.twig', $params);
    }

    /**
     * @Route("/logout", name="website.user.logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}