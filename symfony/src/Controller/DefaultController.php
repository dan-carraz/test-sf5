<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\Twig\Components\AddStuffToDoctrineInterface;
use Elastica\Client;
use Elastica\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\TwigComponent\ComponentFactory;
use Twig\Environment;

class DefaultController extends AbstractController
{
    private const DEFAULT_TEMPLATE = 'default.html.twig';

    public function __construct(private AddressRepository $addressRepository, private Environment $twig, private ComponentFactory $componentFactory)
    {
    }

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
        $source = $this->twig->load(self::DEFAULT_TEMPLATE)->getSourceContext();
        $start = microtime(true);
        $tokens = $this->twig->tokenize($source);
        $components = [];
        while (!$tokens->isEOF()) {
            $token = $tokens->next();

            if ('extends' === $token->getValue()) {
                $extends = $tokens->next();
                $parentTemplateSource = $this->twig->load($extends->getValue())->getSourceContext();
                dump($parentTemplateSource);
                // TODO do recursive stuff
            }

            if ('component' === $token->getValue()) {
                $componentToken = $tokens->next();
                $component = $this->componentFactory->get($componentName = $componentToken->getValue());

                if (!in_array($componentName, $components) && $component instanceof AddStuffToDoctrineInterface) {
                    $components[] = $componentName;
                    dump($component);
                    $component->setQb($this->addressRepository->getQueryBuilder());
                    $component->addQueryParams();
                }
            }
        }

        dump(microtime(true) - $start);

        return $this->render(self::DEFAULT_TEMPLATE, ['number' => $id]);
    }

    #[Route(path: '/test_regex/{id}')]
    public function testRegex(string $id): Response
    {
        $source = $this->twig->load(self::DEFAULT_TEMPLATE)->getSourceContext();
        $start = microtime(true);
        preg_match_all('/\{% component \'(?<componentName>.+)\' %}/', $source->getCode(), $matches);
        $components = [];
        foreach ($matches['componentName'] ?? [] as $componentName) {
            $component = $this->componentFactory->get($componentName);

            if (!in_array($componentName, $components) && $component instanceof AddStuffToDoctrineInterface) {
                $components[] = $componentName;
                dump($component);
                $component->setQb($this->addressRepository->getQueryBuilder());
                $component->addQueryParams();
            }
        }

        dump(microtime(true) - $start);

        return $this->render(self::DEFAULT_TEMPLATE, ['number' => $id]);
    }
}
