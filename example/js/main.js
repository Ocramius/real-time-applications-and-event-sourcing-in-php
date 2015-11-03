(function (window, document, fetch, CustomEvent, FormData) {
    "use strict";

    var bowlingPlayground = document.querySelector('#bowling-playground'),
        activeGames       = document.querySelector('#active-games'),
        createGame        = bowlingPlayground.querySelector('#new-game'),
        commandBus        = 'http://localhost:8888/command-bus.php',
        getNewGames       = 'http://localhost:8888/get-new-games.php',
        pollInterval      = 2000,

        /**
         * creates a poll that isn't interval based, but waits for the previous poll to return a promise
         */
        createPoll        = function (callback, timeout) {
            window.setTimeout(function () {
                callback()
                    .then(function () {
                        createPoll(callback, timeout)
                    })
                    .catch(function () {
                        createPoll(callback, timeout)
                    });
            }, timeout);
        };

    createGame.addEventListener('click', function () {
        fetch(commandBus, {
            method:  'post',
            body:    'command=NewGame',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });
    });

    // replace with web socket/message queue:
    (function () {
        var lastGameId = null;

        createPoll(function () {
            return fetch(getNewGames + (lastGameId ? '?lastGameId=' + lastGameId : ''), {
                method: 'get',
                cache:  'no-cache'
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (responseJson) {
                    responseJson.games.forEach(function (gameId) {
                        bowlingPlayground.dispatchEvent(new CustomEvent('GameStarted', {detail: {gameId: gameId}}));

                        lastGameId = gameId;
                    })
                });

        }, pollInterval);
    }());

    bowlingPlayground.addEventListener('GameStarted', function (gameStarted) {
        console.log(gameStarted);
        var gameLi = document.createElement('li');

        gameLi.innerHTML = '<p class="game-title">Game #' + gameStarted.detail.gameId + '</p><ol class="throws"></ol></p>';

        activeGames.appendChild(gameLi);
    });
}(window, document, fetch, CustomEvent, FormData));
