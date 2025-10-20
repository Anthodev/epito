<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\NameableTrait;
use App\Domain\Trait\TimestampableTrait;
use App\Shared\Enum\ShowStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ApiResource(mercure: true)]
class Show implements ModelInterface
{
    use IdTrait;
    use NameableTrait;
    use TimestampableTrait;

    public function __construct(
        private string $slug,
        private int $idTvmaze,
        private ShowType $type,
        private Network $network,
        /** @var Collection<int, Genre> */
        private Collection $genres,
        /** @var Collection<int, Season> */
        private Collection $seasons,
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
        $this->seasons = new ArrayCollection();
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

    public function getStatus(): ShowStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ShowStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getPremiered(): ?string
    {
        return $this->premiered;
    }

    public function setPremiered(?string $premiered): self
    {
        $this->premiered = $premiered;

        return $this;
    }

    public function getIdTvmaze(): int
    {
        return $this->idTvmaze;
    }

    public function setIdTvmaze(int $idTvmaze): self
    {
        $this->idTvmaze = $idTvmaze;

        return $this;
    }

    public function getIdImdb(): ?int
    {
        return $this->idImdb;
    }

    public function setIdImdb(?int $idImdb): self
    {
        $this->idImdb = $idImdb;

        return $this;
    }

    public function getIdTvdb(): ?int
    {
        return $this->idTvdb;
    }

    public function setIdTvdb(?int $idTvdb): self
    {
        $this->idTvdb = $idTvdb;

        return $this;
    }

    public function getType(): ShowType
    {
        return $this->type;
    }

    public function setType(ShowType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNetwork(): ?Network
    {
        return $this->network;
    }

    public function setNetwork(?Network $network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->contains($season)) {
            $this->seasons->removeElement($season);
        }

        return $this;
    }
}
