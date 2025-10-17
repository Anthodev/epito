<?php

declare(strict_types=1);

namespace App\Domain\Trait;

trait NameableTrait
{
    private string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
