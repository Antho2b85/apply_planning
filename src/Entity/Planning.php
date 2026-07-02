<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Creneau>
     */
    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'planningId')]
    private Collection $creneaus;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->creneaus = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Creneau>
     */
    public function getCreneaus(): Collection
    {
        return $this->creneaus;
    }

    public function addCreneau(Creneau $creneau): static
    {
        if (!$this->creneaus->contains($creneau)) {
            $this->creneaus->add($creneau);
            $creneau->setPlanningId($this);
        }

        return $this;
    }

    public function removeCreneau(Creneau $creneau): static
    {
        if ($this->creneaus->removeElement($creneau)) {
            // set the owning side to null (unless already changed)
            if ($creneau->getPlanningId() === $this) {
                $creneau->setPlanningId(null);
            }
        }

        return $this;
    }
}
