<?php

namespace App\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebsocketTestController extends AbstractController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->render('websocket_test/index.html.twig');
    }
}