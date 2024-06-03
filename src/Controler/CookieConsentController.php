<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CookieConsentController extends AbstractController
{
    private $cookieName = 'user_consent';

    #[Route('/consent', name: 'cookie_consent', methods: ['POST'])]
    public function handleConsent(Request $request): Response
    {
        $consent = $request->request->get('consent', 'no');
        $response = $this->redirect($request->headers->get('referer'));

        if ($consent === 'yes') {
            $response->headers->setCookie(Cookie::create($this->cookieName, 'yes', time() + 365*24*60*60));
        } else {
            $response->headers->clearCookie($this->cookieName);
        }

        return $response;
    }

    public function renderConsentBanner(Request $request): Response
    {
        $consent = $request->cookies->get($this->cookieName, 'no');

        return $this->render('cookie_consent/banner.html.twig', [
            'showBanner' => $consent !== 'yes',
        ]);
    }
}
