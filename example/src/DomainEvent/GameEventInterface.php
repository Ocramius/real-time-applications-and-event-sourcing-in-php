<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\UuidInterface as Uuid;

interface GameEventInterface
{
    public function getGameId() : Uuid;
}
