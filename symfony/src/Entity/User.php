<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource()
 */
class User
{
    public const SEX_MALE = 1;
    public const SEX_FEMALE = 2;
    public const SEX_OTHER = 3;

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
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTimeInterface")
     */
    private ?DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\Choice({User::SEX_MALE, User::SEX_FEMALE, User::SEX_OTHER}, message="Not valid choice. 1: Male, 2: Female, 3: Other")
     */
    private ?int $sex;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user", cascade={"persist", "remove"})
     * @ApiSubresource()
     */
    private PersistentCollection $addresses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function getBirthDateFormated(): ?string
    {
        if ($this->birthDate instanceof DateTimeInterface) {
            return $this->birthDate->format("Y-m-d");
        }

        return null;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSex(): ?int
    {
        return $this->sex;
    }

    public function setSex(int $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getAddresses(): PersistentCollection
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     */
    public function addAddress(Address $address): void
    {
        if ($this->addresses->contains($address)) {
            return;
        }

        $this->addresses[] = $address;
        $address->setUser($this);
    }
}
