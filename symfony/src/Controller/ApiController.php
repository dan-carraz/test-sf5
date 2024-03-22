<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProjectRepository;
use Elastica\Client;
use Elastica\Query;
use Elastica\Result;
use Elastica\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ApiController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    #[Route(
        path: '/api/find-redirect-by-url', name: 'find-redirect-from-url'
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $url = $request->get('url');
        if (!preg_match('/(https:\/\/.*\.fr)(\/.*)/', $url, $matches)) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $host = $matches[1];
        $path = $matches[2];

        $project = $this->projectRepository->findOneBy(['host' => $host]);

        if (!$project) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

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

        $index = $client->getIndex('redirections');
        $search = new Search($client);
        $search->addIndex($index);

        $query = new Query([
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match' => [
                                'pattern' => $path,
                            ],
                        ],
                        [
                            'simple_query_string' => [
                                'query' => $project->getName(),
                                'fields' => [
                                    'project',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $search->setQuery($query);

        $results = $search->search();
        /** @var Result $result */
        foreach ($results->getResults() as $result) {
            $source = $result->getSource();

            if (preg_match("|{$source['pattern']}|", $path)) {
                return new JsonResponse([
                    'redirect-to' => $host.$source['replacement'],
                    'status' => $source['status'],
                ]);
            }
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
