<?php

namespace YourFrog\Panel\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use YourFrog\Panel\Form;
use YourFrog\Panel\Entity;
use YourFrog\Panel\Exceptions\Contest\SettingsNotFoundException;
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
     * @param Service\Contest\ContestSettingsService $settings
     *
     * @return Response
     */
    public function dashboard(Service\Contest\ContestSettingsService $settings): Response
    {
        try {
            $params = [];
            $params['settings'] = $settings->get();
        } catch(SettingsNotFoundException $e) {
            return $this->redirectToRoute('panel.contest.wizard_configuration');
        }

        return $this->render('panel/contest/dashboard.html.twig', $params);
    }

    /**
     *  Wstępna konfiguracja konkursu
     *
     * @Route("/wizard", methods={"GET", "POST"}, name="panel.contest.wizard_configuration")
     */
    public function wizardConfiguration(Service\Contest\ContestSettingsService $settings, Request $request)
    {
        if( $settings->has() ) {
            // We run this page only one time to configure contest. Latter user can edit settings value on dashboard
            return $this->redirectToRoute('panel.contest');
        }

        $form = $this->createForm(Form\Contest\WizardSettings::class);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ) {
            die('Udało się');
        }

        $params = [];
        $params['form'] = $form->createView();

        return $this->render('panel/contest/wizard_configuration.html.twig', $params);
    }
}