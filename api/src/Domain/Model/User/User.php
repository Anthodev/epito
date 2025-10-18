<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ApiResource(mercure: true)]
class User implements ModelInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimestampableTrait;

    public function __construct(
        private string $email,
        private string $username,
        #[Ignore] private ?string $password = null,
        private ?string $plainPassword = null,
        private bool $enabled = false,
        private ?Role $role = null,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        /** @var Role $role */
        $role = $this->role;
        $roleCode = $role->getCode();

        return [$roleCode];
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        /** @phpstan-ignore-next-line */
        return $this->getEmail();
    }
}
