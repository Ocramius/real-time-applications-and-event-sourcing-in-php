<?php

namespace ESBowling;

use Ramsey\Uuid\Uuid;

final class ThrowBall
{
    /**
     * @var Uuid
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
}
