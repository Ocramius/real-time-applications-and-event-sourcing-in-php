<?php

declare(strict_types=1);

namespace ESBowling\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ThrowBall
{
    /**
     * @var UuidInterface
     */
    private $gameId;

    private function __construct()
    {
    }

    /**
     * @param array $postData
     *
     * @return self
     */
    public static function fromPostData(array $postData)
    {
        $instance = new self();

        $instance->gameId = Uuid::fromString($postData['gameId']);

        return $instance;
    }

    public function getGameId() : UuidInterface
    {
        return $this->gameId;
    }
}
