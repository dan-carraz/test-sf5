<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RedirectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RedirectionRepository::class)]
#[ORM\UniqueConstraint(name: 'project_redirect', columns: ['pattern', 'project_id'])]
#[ApiResource]
class Redirection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $pattern = null;

    #[ORM\Column(type: 'smallint', options: ['unsigned' => true])]
    private ?int $status = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $redirectUrl = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): static
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
