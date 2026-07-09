<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SebastianBergmann\Diff\Diff;

#[ORM\Entity(repositoryClass: CreneauRepository::class)]
class Creneau extends AbstractEntity
{
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heureFin = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\ManyToOne(inversedBy: 'creneaus')]
    private ?Planning $planningId = null;

    /**
     * @var Collection<int, UserCreneau>
     */
    #[ORM\OneToMany(targetEntity: UserCreneau::class, mappedBy: 'creneau')]
    private Collection $userCreneaus;

    #[ORM\ManyToOne(inversedBy: 'creneaus')]
    private ?Navire $navire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifAbsence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeShift = null;

    public function __construct()
    {
        $this->userCreneaus = new ArrayCollection();
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTime
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTime $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        $this->calculateWorkTime();
        return $this;
    }

    public function getHeureFin(): ?\DateTime
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTime $heureFin): static
    {
        $this->heureFin = $heureFin;
        $this->calculateWorkTime();
        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPlanningId(): ?Planning
    {
        return $this->planningId;
    }

    public function setPlanningId(?Planning $planningId): static
    {
        $this->planningId = $planningId;

        return $this;
    }

    /**
     * @return Collection<int, UserCreneau>
     */
    public function getUserCreneaus(): Collection
    {
        return $this->userCreneaus;
    }

    public function addUserCreneau(UserCreneau $userCreneau): static
    {
        if (!$this->userCreneaus->contains($userCreneau)) {
            $this->userCreneaus->add($userCreneau);
            $userCreneau->setCreneau($this);
        }

        return $this;
    }

    public function removeUserCreneau(UserCreneau $userCreneau): static
    {
        if ($this->userCreneaus->removeElement($userCreneau)) {
            // set the owning side to null (unless already changed)
            if ($userCreneau->getCreneau() === $this) {
                $userCreneau->setCreneau(null);
            }
        }

        return $this;
    }

    public function getNavire(): ?Navire
    {
        return $this->navire;
    }

    public function setNavire(?Navire $navire): static
    {
        $this->navire = $navire;

        return $this;
    }

    private function calculateWorkTime()
    {
        if (!$this->heureDebut || !$this->heureFin) {
            $this->duree = 0;
        } else {
            $intervalle = ($this->heureDebut)->diff($this->heureFin);
            $this->duree = ($intervalle->h * 60) + $intervalle->i;
        }
    }

    public function getMotifAbsence(): ?string
    {
        return $this->motifAbsence;
    }

    public function setMotifAbsence(?string $motifAbsence): static
    {
        $this->motifAbsence = $motifAbsence;

        return $this;
    }

    public function getTypeShift(): ?string
    {
        return $this->typeShift;
    }

    public function setTypeShift(?string $typeShift): static
    {
        $this->typeShift = $typeShift;

        return $this;
    }
}
