<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $equipe = null;

    #[ORM\Column(nullable: true)]
    private ?bool $firstLogin = null;

    /**
     * @var Collection<int, UserCreneau>
     */
    #[ORM\OneToMany(targetEntity: UserCreneau::class, mappedBy: 'user')]
    private Collection $userCreneaus;

    public function __construct()
    {
        $this->userCreneaus = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEquipe(): ?string
    {
        return $this->equipe;
    }

    public function setEquipe(?string $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function isFirstLogin(): ?bool
    {
        return $this->firstLogin;
    }

    public function setFirstLogin(bool $firstLogin): static
    {
        $this->firstLogin = $firstLogin;

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
            $userCreneau->setUser($this);
        }

        return $this;
    }

    public function removeUserCreneau(UserCreneau $userCreneau): static
    {
        if ($this->userCreneaus->removeElement($userCreneau)) {
            // set the owning side to null (unless already changed)
            if ($userCreneau->getUser() === $this) {
                $userCreneau->setUser(null);
            }
        }

        return $this;
    }
}
