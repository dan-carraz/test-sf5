<?php

namespace App\Factory;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Project>
 *
 * @method        Project|Proxy                     create(array|callable $attributes = [])
 * @method static Project|Proxy                     createOne(array $attributes = [])
 * @method static Project|Proxy                     find(object|array|mixed $criteria)
 * @method static Project|Proxy                     findOrCreate(array $attributes)
 * @method static Project|Proxy                     first(string $sortedField = 'id')
 * @method static Project|Proxy                     last(string $sortedField = 'id')
 * @method static Project|Proxy                     random(array $attributes = [])
 * @method static Project|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProjectRepository|RepositoryProxy repository()
 * @method static Project[]|Proxy[]                 all()
 * @method static Project[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Project[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Project[]|Proxy[]                 findBy(array $attributes)
 * @method static Project[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Project[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Project> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Project> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Project> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Project> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Project> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Project> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Project> random(array $attributes = [])
 * @phpstan-method static Proxy<Project> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Project> repository()
 * @phpstan-method static list<Proxy<Project>> all()
 */
final class ProjectFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'host' => self::faker()->text(125),
            'name' => self::faker()->text(125),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Project $project): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Project::class;
    }
}
