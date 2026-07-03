<?php

namespace App\Entity;

use App\Repository\NavireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavireRepository::class)]
class Navire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Creneau>
     */
    #[ORM\OneToMany(targetEntity: Creneau::class, mappedBy: 'navire')]
    private Collection $creneaus;

    public function __construct()
    {
        $this->creneaus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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
            $creneau->setNavire($this);
        }

        return $this;
    }

    public function removeCreneau(Creneau $creneau): static
    {
        if ($this->creneaus->removeElement($creneau)) {
            // set the owning side to null (unless already changed)
            if ($creneau->getNavire() === $this) {
                $creneau->setNavire(null);
            }
        }

        return $this;
    }
}
