<?php

namespace YourFrog\Website\Controller;

use YourFrog\App\Repository;
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
     *
     * @param Repository\Security\AccountAuthenticationHistoryRepository $repository
     */
    public function authenticateHistory(Repository\Security\AccountAuthenticationHistoryRepository $repository): Response
    {
        $params = [];
        $params['items'] = $repository->findByUser($this->getUser());

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