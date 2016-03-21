var gamesController = ['$scope', 'Game', 'Participation', 'Bet', function($scope, Game, Participation, Bet){
    $scope.games = [];
    $scope.participations = [];
    $scope.bet = [];
    $scope.order = 'startTime';

    Bet.query(function(bets){
        $scope.bets = bets;
        Participation.query(function(participations){
            $scope.participations = participations;
            Game.query(function(games){
                $scope.games = games;
            });
        });
    });
}];

var gameController = ['$scope', function($scope){
    return {
        getBet: function(participation, game){
            for(var i = 0; i < $scope.bets.length; i++){
                if($scope.bets[i].pot.id == participation.pot.id && $scope.bets[i].game.id == game.id){
                    return $scope.bets[i];
                }
            }
            return null;
        }
    };
}];

var betController = ['$scope', 'Bet', function($scope, Bet){
    return {
        update: function(bet){
            return Bet.save({
                id: bet.id
            },{
                score1: bet.score1,
                score2: bet.score2
            });
        },
        create: function(bet){
            return Bet.save({},{
                score1: bet.score1,
                score2: bet.score2,
                game: $scope.game.id,
                pot: $scope.participation.pot.id
            });
        }
        delete: function(bet){
            console.log('bet ' + bet.id + ' deleted');
            bet.$delete({id: bet.id}, function(){
                $scope.bet = null;
            });
        }
    };
}];

var controllers = angular.module('controllers',[
]);

controllers.controller('GamesController', gamesController);
