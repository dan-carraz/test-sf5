<?php

namespace App\Factory;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static        Address|Proxy createOne(array $attributes = [])
 * @method static        Address[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static        Address|Proxy find($criteria)
 * @method static        Address|Proxy findOrCreate(array $attributes)
 * @method static        Address|Proxy first(string $sortedField = 'id')
 * @method static        Address|Proxy last(string $sortedField = 'id')
 * @method static        Address|Proxy random(array $attributes = [])
 * @method static        Address|Proxy randomOrCreate(array $attributes = [])
 * @method static        Address[]|Proxy[] all()
 * @method static        Address[]|Proxy[] findBy(array $attributes)
 * @method static        Address[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static        Address[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static        AddressRepository|RepositoryProxy repository()
 * @method Address|Proxy create($attributes = [])
 */
final class AddressFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'street' => self::faker()->address,
            'streetBis' => 1 === mt_rand(0, 1) ? self::faker()->sentence : null,
            'zipCode' => self::faker()->postcode,
            'country' => self::faker()->country,
            'city' => self::faker()->city,
            'state' => 1 === mt_rand(0, 1) ? self::faker()->state : null,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Address $address) {})
        ;
    }

    protected static function getClass(): string
    {
        return Address::class;
    }
}
