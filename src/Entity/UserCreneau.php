<?php

namespace App\Entity;

use App\Repository\UserCreneauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCreneauRepository::class)]
class UserCreneau extends AbstractEntity
{
    #[ORM\ManyToOne(inversedBy: 'userCreneaus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userCreneaus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Creneau $creneau = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreneau(): ?Creneau
    {
        return $this->creneau;
    }

    public function setCreneau(?Creneau $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }
}
