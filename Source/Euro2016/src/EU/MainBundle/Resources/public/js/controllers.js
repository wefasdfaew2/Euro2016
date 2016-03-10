var gameController = function(){
    this.games = [
        {
            id: 1
        },
        {
            id: 2
        }
    ];
};

var controllers = angular.module('controllers',[
]);

controllers.controller('GameController', gameController);
