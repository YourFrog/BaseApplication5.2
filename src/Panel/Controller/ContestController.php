<?php

namespace YourFrog\Panel\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use YourFrog\Panel\Entity;
use YourFrog\Panel\Service;

/**
 *  Kontroller obsługujący pojedyńczy konkurs
 *
 * @Route("/contest")
 */
class ContestController extends AbstractController
{
    /**
     *  Strona główna zarządzania konkursem
     *
     * @Route("/", methods="GET", name="panel.contest")
     *
     * @param Service\Contest\GetContestSettingsService $getSettingsService
     *
     * @return Response
     */
    public function dashboard(Service\Contest\GetContestSettingsService $getSettingsService): Response
    {
        $params = [];
        $params['settings'] = $getSettingsService->getSettings();

        return $this->render('panel/contest/dashboard.html.twig', $params);
    }
}