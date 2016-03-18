var gamesController = ['$scope', 'Game', 'Participation', 'Bet', function($scope, Game, Participation, Bet){
    $scope.games = [];
    $scope.participations = [];
    $scope.bets = [];
    $scope.order = 'startTime';

    Game.query(function(games){
        $scope.games = games;
    });
    Participation.query(function(participations){
        $scope.participations = participations;
    });
    Bet.query(function(bets){
        $scope.bets = bets;
    });
}];

var betController = ['$scope', 'Bet', function($scope, Bet){
    return {
        findBet: function(p){
            for(b of $scope.bets){
                if(b.pot.id == p.pot.id && b.game.id == $scope.game.id){
                    return b;
                }
            }
        },
        save: function(){
            console.log('bet saved');
        },
        delete: function(){
            console.log('bet deleted');
        },
        create: function(){
            console.log('bet created');
        }
    };
}];

var controllers = angular.module('controllers',[
]);

controllers.controller('GamesController', gamesController);
//controllers.controller('BetController', betController);
