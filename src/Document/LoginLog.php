<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

// Document MongoDB pour les logs de connexion
#[ODM\Document(collection: "login_logs")]
class LoginLog
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    private ?string $email = null;

    #[ODM\Field(type: "string")]
    private ?string $role = null;

    #[ODM\Field(type: "date")]
    private ?\DateTime $connectedAt = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getConnectedAt(): ?\DateTime
    {
        return $this->connectedAt;
    }

    public function setConnectedAt(\DateTime $connectedAt): static
    {
        $this->connectedAt = $connectedAt;
        return $this;
    }
}
