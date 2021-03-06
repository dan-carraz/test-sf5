<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $streetBis;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private ?string $zipCode;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private ?string $state;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank()
     */
    private ?string $country;

    /**
     * @var User|null
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addresses")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private ?User $user;

    public function getId(): ?int
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

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(User $user = null): void
    {
        $this->user = $user;

        if ($this->user instanceof User) {
            $this->user->addAddress($this);
        }
    }
}
