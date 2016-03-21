var gameFactory = function ($resource){
    var Game = $resource('games/:id',{},{
        'getBets': {
            method: 'GET',
            url: 'games/:id/bets',
            isArray: true
        }
    });
    return Game;
};

var participationFactory = function ($resource){
    return $resource('participations/:id');
}

var betFactory = function ($resource){
    var Bet = $resource('bets/:id');
    return Bet;
}

var teamFactory = function ($resource){
    return $resource('teams/:id');
}

var services = angular.module('services',[
]);

services.factory('Game', gameFactory);
services.factory('Participation', participationFactory);
services.factory('Bet', betFactory);
services.factory('Team', teamFactory);
