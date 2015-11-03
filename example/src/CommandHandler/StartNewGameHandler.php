<?php

declare(strict_types=1);

namespace ESBowling\CommandHandler;

use ESBowling\Aggregate\Game;
use ESBowling\Command\StartNewGame;
use ESBowling\Repository\GameRepository;

final class StartNewGameHandler
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(StartNewGame $startNewGame)
    {
        $this->gameRepository->save(Game::newGame());
    }
}
