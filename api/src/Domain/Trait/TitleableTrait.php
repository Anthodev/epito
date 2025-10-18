<?php

declare(strict_types=1);

namespace App\Domain\Trait;

trait TitleableTrait
{
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
