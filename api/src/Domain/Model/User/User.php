<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Model\Common\ModelInterface;
use App\Domain\Model\Show\Following;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;

class User implements ModelInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    use IdTrait;
    use TimestampableTrait;

    public function __construct(
        private string $email,
        private string $username,
        /** @var Collection<int, Following> */
        private Collection $followings = new ArrayCollection(),
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

    /**
     * @return Collection<int, Following>
     */
    public function getFollowings(): Collection
    {
        return $this->followings;
    }

    public function addFollowing(Following $following): self
    {
        if (!$this->followings->contains($following)) {
            $this->followings->add($following);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->contains($following)) {
            $this->followings->removeElement($following);
        }

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
