<?php

declare(strict_types=1);

namespace App\Domain\Model\Show;

use App\Domain\Model\Common\ModelInterface;
use App\Domain\Trait\IdTrait;
use App\Domain\Trait\NameableTrait;
use App\Domain\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ShowType implements ModelInterface
{
    use IdTrait;
    use NameableTrait;
    use TimestampableTrait;

    public function __construct(
        /** @var Collection<int, Show> */
        private Collection $shows = new ArrayCollection(),
    ) {
    }

    /**
     * @return Collection<int, Show>
     */
    public function getShows(): Collection
    {
        return $this->shows;
    }

    public function addShow(Show $show): void
    {
        if (!$this->shows->contains($show)) {
            $this->shows->add($show);
        }
    }

    public function removeShow(Show $show): void
    {
        if ($this->shows->contains($show)) {
            $this->shows->removeElement($show);
        }
    }
}
