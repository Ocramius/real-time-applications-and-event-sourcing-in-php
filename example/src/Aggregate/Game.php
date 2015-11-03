<?php

declare(strict_types=1);

namespace ESBowling\Aggregate;

use Ramsey\Uuid\Uuid;

final class Game
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var object[]
     */
    private $gameEvents = [];

    private function __construct()
    {
    }

    public static function newGame()
    {
        $instance = new self();

        $instance->id = Uuid::uuid4();

        return $instance;
    }

    /**
     * @return void
     */
    public function throwBall()
    {
    }
}
