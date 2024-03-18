<?php

namespace App\Factory;

use App\Entity\Redirection;
use App\Repository\RedirectionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Redirection>
 *
 * @method        Redirection|Proxy                     create(array|callable $attributes = [])
 * @method static Redirection|Proxy                     createOne(array $attributes = [])
 * @method static Redirection|Proxy                     find(object|array|mixed $criteria)
 * @method static Redirection|Proxy                     findOrCreate(array $attributes)
 * @method static Redirection|Proxy                     first(string $sortedField = 'id')
 * @method static Redirection|Proxy                     last(string $sortedField = 'id')
 * @method static Redirection|Proxy                     random(array $attributes = [])
 * @method static Redirection|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RedirectionRepository|RepositoryProxy repository()
 * @method static Redirection[]|Proxy[]                 all()
 * @method static Redirection[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Redirection[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Redirection[]|Proxy[]                 findBy(array $attributes)
 * @method static Redirection[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Redirection[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @phpstan-method        Proxy<Redirection> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Redirection> createOne(array $attributes = [])
 * @phpstan-method static Proxy<Redirection> find(object|array|mixed $criteria)
 * @phpstan-method static Proxy<Redirection> findOrCreate(array $attributes)
 * @phpstan-method static Proxy<Redirection> first(string $sortedField = 'id')
 * @phpstan-method static Proxy<Redirection> last(string $sortedField = 'id')
 * @phpstan-method static Proxy<Redirection> random(array $attributes = [])
 * @phpstan-method static Proxy<Redirection> randomOrCreate(array $attributes = [])
 * @phpstan-method static RepositoryProxy<Redirection> repository()
 * @phpstan-method static list<Proxy<Redirection>> all()
 */
final class RedirectionFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'pattern' => sprintf('^/%s$', self::faker()->unique()->randomElement([
                self::randomUrlPatternPart(),
                self::randomUrlPatternPart().'/'.self::randomUrlPatternPart(),
                self::randomUrlPatternPart().'/'.self::randomUrlPatternPart().'/'.self::randomUrlPatternPart(),
                ])),
            'project' => ProjectFactory::random(),
            'status' => self::faker()->randomElement([410, 301]),
            'redirectUrl' => self::faker()->randomElement([self::faker()->url(), '/'.self::faker()->word()]),
        ];
    }

    private static function randomUrlPatternPart(): string
    {
        $nbLetters = mt_rand(3, 25);
        $pattern = '';

        for ($i = 0; $i < $nbLetters; ++$i) {
            $pattern .= self::faker()->randomElement([
                self::faker()->randomLetter(),
                self::faker()->randomDigit(),
            ]);
        }

        return $pattern;
    }

    protected static function getClass(): string
    {
        return Redirection::class;
    }
}
