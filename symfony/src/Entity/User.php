<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    public const SEX_MALE = 1;
    public const SEX_FEMALE = 2;
    public const SEX_OTHER = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[Groups(['read'])]
    private ?string $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private ?string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    private ?string $lastName;

    #[ORM\Column(type: 'date')]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: "\DateTimeInterface")]
    private ?DateTimeInterface $birthDate;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['write'])]
    #[Assert\NotBlank]
    #[Assert\Choice([self::SEX_MALE, self::SEX_FEMALE, self::SEX_OTHER], message: 'Not valid choice. 1: Male, 2: Female, 3: Other')]
    private ?int $sex;

    #[ApiSubresource]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist', 'remove'])]
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

    #[Groups(['read'])]
    public function getBirthDateFormatted(): ?string
    {
        if ($this->birthDate instanceof DateTimeInterface) {
            return $this->birthDate->format('Y-m-d');
        }

        return null;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    #[Groups(['read'])]
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

    public function getAddresses(): PersistentCollection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): void
    {
        if ($this->addresses->contains($address)) {
            return;
        }

        $this->addresses[] = $address;
        $address->setUser($this);
    }
}
