<?php


namespace YourFrog\Panel\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *  Podstawowy kontroller panelu
 *
 * @package YourFrog\Website\Controller
 */
class IndexController extends AbstractController
{
    /**
     *  Strona główna aplikacji
     *
     * @Route("/", methods="GET", name="panel.homepage")
     */
    public function homepage(): Response
    {
        return $this->render('panel/index/homepage.html.twig');
    }
}