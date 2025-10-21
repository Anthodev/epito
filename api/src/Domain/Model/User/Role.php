<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Role implements ModelInterface
{
    use IdTrait;
    use TimestampableTrait;

    public function __construct(
        private string $code,
        private ?string $label = null,
        /** @var Collection<int, User> $users */
        private Collection $users = new ArrayCollection(),
    ) {
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}
