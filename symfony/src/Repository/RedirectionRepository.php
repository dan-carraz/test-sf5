<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Redirection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Redirection>
 *
 * @method Redirection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Redirection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Redirection[]    findAll()
 * @method Redirection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedirectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redirection::class);
    }

    public function createRedirections(Project $project, array $redirections): void
    {
        $connection = $this->getEntityManager()->getConnection();

        $query = 'INSERT IGNORE INTO redirection (project_id, pattern, redirect_url, status) VALUES ';
        $params = ['project_id' => $project->getId()];
        foreach ($redirections as $index => $redirection) {
            if (0 !== $index) {
                $query .= ', ';
            }
            $query .= "(:project_id, :pattern_$index, :redirection_$index, 301)";
            $params["pattern_$index"] = $redirection[0];
            $params["redirection_$index"] = $redirection[1];
        }

        $statement = $connection->prepare($query);

        $statement->executeStatement($params);
    }
}
