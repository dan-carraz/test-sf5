<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\UserSex;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(normalizationContext: ['groups' => ['read']], denormalizationContext: ['groups' => ['write']])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
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
    private ?\DateTimeInterface $birthDate;

    #[ORM\Column(type: 'smallint', enumType: UserSex::class)]
    #[Groups(['write'])]
    #[Assert\NotBlank]
    #[Assert\Choice([UserSex::Male, UserSex::Female, UserSex::Other], message: 'Not valid choice. 1: Male, 2: Female, 3: Other')]
    private ?UserSex $sex;

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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    #[Groups(['read'])]
    public function getBirthDateFormatted(): ?string
    {
        if ($this->birthDate instanceof \DateTimeInterface) {
            return $this->birthDate->format('Y-m-d');
        }

        return null;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    #[Groups(['read'])]
    public function getSexFormatted(): ?string
    {
        return $this->sex instanceof UserSex ? $this->sex->getSexFormatted() : null;
    }

    public function getSex(): ?UserSex
    {
        return $this->sex;
    }

    public function setSex(int|UserSex $sex): self
    {
        if (!$sex instanceof UserSex) {
            $sex = UserSex::from($sex);
        }

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

        $this->addresses->add($address);
        $address->setUser($this);
    }
}
