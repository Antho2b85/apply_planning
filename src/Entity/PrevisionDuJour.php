<?php

namespace App\Entity;

use App\Repository\PrevisionDuJourRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevisionDuJourRepository::class)]
class PrevisionDuJour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agentsReserves = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalRemorques = null;

    #[ORM\Column(nullable: true)]
    private ?int $embarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $titres = null;

    #[ORM\Column(nullable: true)]
    private ?int $attentes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agentsControle = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalPassagers = null;

    #[ORM\Column(nullable: true)]
    private ?int $autosBasses = null;

    #[ORM\Column(nullable: true)]
    private ?int $hauteurs = null;

    #[ORM\Column(nullable: true)]
    private ?int $attelages = null;

    #[ORM\Column(nullable: true)]
    private ?int $motos = null;

    #[ORM\Column(nullable: true)]
    private ?int $controles = null;

    #[ORM\Column(nullable: true)]
    private ?int $aVenir = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $position = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgentsReserves(): ?string
    {
        return $this->agentsReserves;
    }

    public function setAgentsReserves(?string $agentsReserves): static
    {
        $this->agentsReserves = $agentsReserves;

        return $this;
    }

    public function getTotalRemorques(): ?int
    {
        return $this->totalRemorques;
    }

    public function setTotalRemorques(?int $totalRemorques): static
    {
        $this->totalRemorques = $totalRemorques;

        return $this;
    }

    public function getEmbarque(): ?int
    {
        return $this->embarque;
    }

    public function setEmbarque(?int $embarque): static
    {
        $this->embarque = $embarque;

        return $this;
    }

    public function getTitres(): ?int
    {
        return $this->titres;
    }

    public function setTitres(?int $titres): static
    {
        $this->titres = $titres;

        return $this;
    }

    public function getAttentes(): ?int
    {
        return $this->attentes;
    }

    public function setAttentes(?int $attentes): static
    {
        $this->attentes = $attentes;

        return $this;
    }

    public function getAgentsControle(): ?string
    {
        return $this->agentsControle;
    }

    public function setAgentsControle(?string $agentsControle): static
    {
        $this->agentsControle = $agentsControle;

        return $this;
    }

    public function getTotalPassagers(): ?int
    {
        return $this->totalPassagers;
    }

    public function setTotalPassagers(?int $totalPassagers): static
    {
        $this->totalPassagers = $totalPassagers;

        return $this;
    }

    public function getAutosBasses(): ?int
    {
        return $this->autosBasses;
    }

    public function setAutosBasses(?int $autosBasses): static
    {
        $this->autosBasses = $autosBasses;

        return $this;
    }

    public function getHauteurs(): ?int
    {
        return $this->hauteurs;
    }

    public function setHauteurs(?int $hauteurs): static
    {
        $this->hauteurs = $hauteurs;

        return $this;
    }

    public function getAttelages(): ?int
    {
        return $this->attelages;
    }

    public function setAttelages(?int $attelages): static
    {
        $this->attelages = $attelages;

        return $this;
    }

    public function getMotos(): ?int
    {
        return $this->motos;
    }

    public function setMotos(?int $motos): static
    {
        $this->motos = $motos;

        return $this;
    }

    public function getControles(): ?int
    {
        return $this->controles;
    }

    public function setControles(?int $controles): static
    {
        $this->controles = $controles;

        return $this;
    }

    public function getAVenir(): ?int
    {
        return $this->aVenir;
    }

    public function setAVenir(?int $aVenir): static
    {
        $this->aVenir = $aVenir;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
