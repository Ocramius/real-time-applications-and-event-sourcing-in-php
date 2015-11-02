(function (window, document, fetch, CustomEvent) {
    "use strict";

    var bowlingPlayground = document.querySelector('#bowling-playground'),
        createGame        = bowlingPlayground.querySelector('#new-game'),
        commandBus        = 'http://localhost:8888/command-bus.php',
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
            method: 'post',
            body: 'command=NewGame'
        });
    });

    bowlingPlayground.dispatchEvent(new CustomEvent(''));
}(window, document, fetch, CustomEvent));
