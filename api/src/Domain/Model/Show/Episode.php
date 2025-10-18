<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;
use App\Domain\Trait\TitleableTrait;

#[ApiResource(mercure: true)]
class Episode implements ModelInterface
{
    use IdTrait;
    use TitleableTrait;
    use TimestampableTrait;

    public function __construct(
        private int $number,
        private \DateTime $airstamp,
        private Season $season,
        private ?string $summary = null,
        private ?int $runtime = null,
        private ?string $image = null,
    ) {
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getAirstamp(): \DateTime
    {
        return $this->airstamp;
    }

    public function setAirstamp(\DateTime $airstamp): self
    {
        $this->airstamp = $airstamp;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSeason(): Season
    {
        return $this->season;
    }

    public function setSeason(Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
