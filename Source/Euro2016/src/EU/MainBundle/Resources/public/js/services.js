var gameFactory = function ($resource){
    return $resource('games/:id');
};

var participationFactory = function ($resource){
    return $resource('participations/:id');
}

var betFactory = function ($resource){
    return $resource('bets/:id');
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
