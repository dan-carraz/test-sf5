<?php

namespace App\Controller;

use Elastica\Client;
use Elastica\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private const DEFAULT_TEMPLATE = 'default.html.twig';

    #[Route(path: '/')]
    public function index(): Response
    {
        $client = new Client([
            'curl' => [
                \CURLOPT_SSL_VERIFYPEER => 0,
                \CURLOPT_SSL_VERIFYHOST => 0,
            ],
            'host' => 'elastic',
            'port' => 9200,
            'auth_type' => 'basic',
            'transport' => 'https',
            'username' => 'elastic',
            'password' => 'elastic',
        ]);

        $index = $client->getIndex('figaro');

        $index->create([], ['recreate' => true]);

        $index->addDocuments([
            new Document('foo', ['pattern' => '^\/toto(\/?\?.*)?$', 'replacement' => '/tata', 'status' => 301]),
            new Document('bar', ['pattern' => '^\/toto/madame$', 'replacement' => '/fii', 'status' => 301]),
            new Document('baz', ['pattern' => '^\/bar$',  'status' => 410]),
        ]);

        return $this->render(self::DEFAULT_TEMPLATE, ['number' => 2]);
    }

    #[Route(path: '/test/{id}')]
    public function test(string $id): Response
    {
        return $this->render(self::DEFAULT_TEMPLATE, ['number' => $id]);
    }
}
