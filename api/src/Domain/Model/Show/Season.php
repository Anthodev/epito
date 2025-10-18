<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ApiResource(mercure: true)]
class Season implements ModelInterface
{
    use IdTrait;
    use TimestampableTrait;

    public function __construct(
        private int $number,
        /** @var Collection<int, Episode> */
        private Collection $episodes,
        private Show $tvShow,
        private int $episodeCount = 0,
        private ?string $poster = null,
        private ?\DateTime $premiereDate = null,
        private ?\DateTime $endDate = null,
    ) {
        $this->episodes = new ArrayCollection();
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

    public function getEpisodeCount(): int
    {
        return $this->episodeCount;
    }

    public function setEpisodeCount(int $episodeCount): self
    {
        $this->episodeCount = $episodeCount;

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

    public function getPremiereDate(): ?\DateTime
    {
        return $this->premiereDate;
    }

    public function setPremiereDate(?\DateTime $premiereDate): self
    {
        $this->premiereDate = $premiereDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTvShow(): Show
    {
        return $this->tvShow;
    }

    public function setTvShow(Show $tvShow): self
    {
        $this->tvShow = $tvShow;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->contains($episode)) {
            $this->episodes->removeElement($episode);
        }

        return $this;
    }
}
