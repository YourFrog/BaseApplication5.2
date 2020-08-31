<?php


namespace YourFrog\Website\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 *  Podstawowy kontroller strony ogólno dostępnej
 *
 * @package YourFrog\Website\Controller
 */
class IndexController extends AbstractController
{
    /**
     *  Strona główna aplikacji
     *
     * @Route("/", name="website.homepage")
     *
     * @param Request $request
     * @param Security $security
     * @param AuthenticationUtils $helper
     *
     * @return Response
     */
    public function homepage(Request $request, Security $security, AuthenticationUtils $helper): Response
    {
        if( $request->isMethod('POST') && $security->isGranted('ROLE_USER') ) {
            // Jeżeli klient został zalogowany to przenieś go na jego ekran, ale tylko wtedy gdy użytkownik wysłał posta
            return $this->redirectToRoute('website.user.dashboard');
        }

        $params = [];
        $params['last_username'] = $helper->getLastUsername();
        $params['error'] = $helper->getLastAuthenticationError(false) ? $helper->getLastAuthenticationError()->getMessage() : null;

        return $this->render('website/index/homepage.html.twig', $params);
    }
}