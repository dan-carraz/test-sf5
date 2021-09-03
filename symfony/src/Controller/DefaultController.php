<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private const DEFAULT_TEMPLATE = 'default.html.twig';

    #[Route(path: '/')]
    public function index(): Response
    {
        return $this->render(self::DEFAULT_TEMPLATE, ['number' => 2]);
    }

    #[Route(path: '/test/{id}')]
    public function test(string $id): Response
    {
        return $this->render(self::DEFAULT_TEMPLATE, ['number' => $id]);
    }
}
