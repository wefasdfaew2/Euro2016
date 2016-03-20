var modalVisible = false;
function showModalBet(game, participation, bet){
    modalVisible = true;
    $('.bet-modal').modal('show');
    console.log('show modal for game '+ game.id + ' participation ' + participation.id);
}

function hideModalBet(){
    modalVisible = false;
    console.log('hide modal');
}

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
        }
    };
}];

var betController = ['$scope', 'Bet', function($scope, Bet){
    return {
        update: function(){
            console.log('bet saved');
        },
        delete: function(bet){
            console.log('bet ' + bet.id + ' deleted');
            bet.$delete({id: bet.id}, function(){
                $scope.bet = null;
            });
        },
        create: function(){
            console.log('bet created');
        }
    };
}];

var controllers = angular.module('controllers',[
]);

controllers.controller('GamesController', gamesController);
