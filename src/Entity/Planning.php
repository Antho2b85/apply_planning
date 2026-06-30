<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning extends AbstractEntity
{
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDebutSemaine = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getDateDebutSemaine(): ?\DateTime
    {
        return $this->dateDebutSemaine;
    }

    public function setDateDebutSemaine(\DateTime $dateDebutSemaine): static
    {
        $this->dateDebutSemaine = $dateDebutSemaine;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
