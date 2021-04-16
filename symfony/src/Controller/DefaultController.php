<?php
namespace App\Controller;

use JetBrains\PhpStorm\ArrayShape;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @Template("default.html.twig")
     * @return array
     */
    #[ArrayShape(["number" => "int"])]
    public function index() : array
    {
        return ["number" => 2];
    }

    /**
     * @Route("/test/{id}")
     * @Template("default.html.twig")
     * @param string $id
     * @return array
     */
    #[ArrayShape(["number" => "string"])]
    public function test(string $id): array
    {
        return ["number" => $id];
    }
}
