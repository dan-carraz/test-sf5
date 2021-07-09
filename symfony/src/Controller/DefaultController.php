<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @Template("default.html.twig")
     */
    public function index(): array
    {
        return ['number' => 2];
    }

    /**
     * @Route("/test/{id}")
     * @Template("default.html.twig")
     *
     * @param string $id
     *
     * @return array
     */
    public function test(string $id): array
    {
        return ['number' => $id];
    }
}
