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

var paidParticipationsFilter = function (){
    return function (array){
        var filtered = [];
        angular.forEach(array, function(item){
            if(item.paidAt){
                filtered.push(item);
            }
        });
        return filtered;
    };
};

var controllers = angular.module('controllers',[
]);

controllers.controller('GamesController', gamesController);
controllers.filter('paidParticipationsFilter', paidParticipationsFilter);
