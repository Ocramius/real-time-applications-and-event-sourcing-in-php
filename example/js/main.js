(function (document, fetch) {
    var bowlingPlayground = document.querySelector('#bowling-playground'),
        createGame        = bowlingPlayground.querySelector('#new-game'),
        commandBus        = 'http://localhost:8888/command-bus.php';

    createGame.addEventListener('click', function () {
        fetch(commandBus, {
            method: 'post',
            body: 'command=NewGame'
        });
    });
}(document, fetch));
