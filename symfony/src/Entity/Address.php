<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(uriTemplate: '/users/{id}/addresses.{_format}', operations: [new GetCollection()], uriVariables: ['id' => new Link(fromClass: User::class, identifiers: ['id'])], status: 200)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    #[ORM\Column(type: 'ulid', unique: true)]
    private ?string $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $street;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $streetBis;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private ?string $zipCode;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private ?string $city;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $state;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    private ?string $country;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(referencedColumnName: 'id')]
    #[Assert\NotBlank]
    private ?User $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetBis(): ?string
    {
        return $this->streetBis;
    }

    public function setStreetBis(?string $streetBis): self
    {
        $this->streetBis = $streetBis;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user = null): void
    {
        $this->user = $user;

        if ($this->user instanceof User) {
            $this->user->addAddress($this);
        }
    }
}
