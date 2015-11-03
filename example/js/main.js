(function (window, document, fetch, CustomEvent, FormData) {
    "use strict";

    var bowlingPlayground = document.querySelector('#bowling-playground'),
        activeGames       = document.querySelector('#active-games'),
        createGame        = bowlingPlayground.querySelector('#new-game'),
        commandBus        = 'http://localhost:8888/command-bus.php',
        getNewGames       = 'http://localhost:8888/get-new-games.php',
        getNewGameEvents  = 'http://localhost:8888/get-new-game-events.php',
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
            body:    'command=StartNewGame',
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
        var gameLi = document.createElement('li'),
            offset = 0;

        gameLi.innerHTML = '<p class="game-title">' +
            '<input type="button" class="throw-ball" value="Throw Ball"/>' +
            'Game #' +
            gameStarted.detail.gameId +
            '</p><ol class="throws"></ol></p>';

        gameLi
            .querySelector('.throw-ball')
            .addEventListener('click', function () {
                console.log(gameStarted.detail.gameId);
            });

        createPoll(function () {
            return fetch(getNewGameEvents + '?gameId=' + gameStarted.detail.gameId + '&offset=' + offset, {
                method: 'get',
                cache:  'no-cache'
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (responseJson) {
                    responseJson.events.forEach(function (event) {
                        gameLi.dispatchEvent(new CustomEvent('GameEvent', {detail: {event: event}}));

                        offset += 1;
                    })
                });

        }, pollInterval);

        activeGames.appendChild(gameLi);
    });
}(window, document, fetch, CustomEvent, FormData));
