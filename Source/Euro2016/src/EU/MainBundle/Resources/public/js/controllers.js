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

var betsController = ['$scope', 'Bet', function($scope, Bet){
    $scope.bets = [];
    $scope.order = 'createdAt';

    Bet.query(function(bets){
        $scope.bets = bets;
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
controllers.controller('BetsController', betsController);
controllers.filter('paidParticipationsFilter', paidParticipationsFilter);
