<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\NameableTrait;
use App\Domain\Trait\TimestampableTrait;
use App\Shared\Enum\ShowStatusEnum;

#[ApiResource(mercure: true)]
class Show implements ModelInterface
{
    use IdTrait;
    use NameableTrait;
    use TimestampableTrait;

    public function __construct(
        private string $slug,
        private int $idTvmaze,
        private ?string $summary = null,
        private ShowStatusEnum $status = ShowStatusEnum::IN_DEVELOPMENT,
        private ?string $poster = null,
        private ?string $website = null,
        private ?float $rating = null,
        private ?string $language = null,
        private ?int $runtime = null,
        private ?string $premiered = null,
        private ?int $idImdb = null,
        private ?int $idTvdb = null,
    ) {
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    public function getStatus(): ShowStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ShowStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): void
    {
        $this->poster = $poster;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): void
    {
        $this->rating = $rating;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(?int $runtime): void
    {
        $this->runtime = $runtime;
    }

    public function getPremiered(): ?string
    {
        return $this->premiered;
    }

    public function setPremiered(?string $premiered): void
    {
        $this->premiered = $premiered;
    }

    public function getIdTvmaze(): int
    {
        return $this->idTvmaze;
    }

    public function setIdTvmaze(int $idTvmaze): void
    {
        $this->idTvmaze = $idTvmaze;
    }

    public function getIdImdb(): ?int
    {
        return $this->idImdb;
    }

    public function setIdImdb(?int $idImdb): void
    {
        $this->idImdb = $idImdb;
    }

    public function getIdTvdb(): ?int
    {
        return $this->idTvdb;
    }

    public function setIdTvdb(?int $idTvdb): void
    {
        $this->idTvdb = $idTvdb;
    }
}
