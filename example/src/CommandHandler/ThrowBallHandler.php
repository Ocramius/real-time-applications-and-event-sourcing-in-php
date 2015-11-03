<?php

declare(strict_types=1);

namespace ESBowling\CommandHandler;

use ESBowling\Aggregate\Game;
use ESBowling\Command\StartNewGame;
use ESBowling\Command\ThrowBall;
use ESBowling\Repository\GameRepository;

final class ThrowBallHandler
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(ThrowBall $throwBall)
    {
        $game = $this->gameRepository->get($throwBall->getGameId());

        $game->throwBall();

        $this->gameRepository->save($game);
    }
}
