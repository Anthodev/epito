<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use App\Domain\Model\Common\ModelInterface;
use App\Domain\Model\User\User;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\TimestampableTrait;

class Following implements ModelInterface
{
    use IdTrait;
    use TimestampableTrait;

    public function __construct(
        private User $user,
        private Episode $episode,
        private Season $season,
        private Show $show,
        private \DateTime $startDate = new \DateTime(),
        private ?\DateTime $endDate = null,
    ) {
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    public function setEpisode(Episode $episode): self
    {
        $this->episode = $episode;

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

    public function getShow(): Show
    {
        return $this->show;
    }

    public function setShow(Show $show): self
    {
        $this->show = $show;

        return $this;
    }
}
