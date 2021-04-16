<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
class User
{
    public const SEX_MALE = 1;
    public const SEX_FEMALE = 2;
    public const SEX_OTHER = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     * @ORM\Column(type="ulid", unique=true)
     * @Groups({"read"})
     */
    private ?string $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTimeInterface")
     * @Groups({"read", "write"})
     */
    private ?DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\Choice({User::SEX_MALE, User::SEX_FEMALE, User::SEX_OTHER}, message="Not valid choice. 1: Male, 2: Female, 3: Other")
     * @Groups({"read", "write"})
     */
    private ?int $sex;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user", cascade={"persist", "remove"})
     * @ApiSubresource()
     * @Groups({"read"})
     */
    private PersistentCollection $addresses;

    public function getId(): ?string
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

    /**
     * @Groups({"read"})
     * @return string|null
     */
    public function getBirthDateFormatted(): ?string
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

    /**
     * @Groups({"read"})
     * @return string|null
     */
    public function getSexFormatted(): ?string
    {
        return match ($this->sex) {
            self::SEX_FEMALE => 'Femme',
            self::SEX_MALE => 'Homme',
            self::SEX_OTHER => 'Autre',
            default => null,
        };
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
