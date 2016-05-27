var gamesController = ['$scope', '$location', 'Game', 'Participation', 'Bet', function($scope, $location, Game, Participation, Bet){
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
                var path = location.pathname;
                var reg = path.match(/(\d+)/);
                if(reg)
                {
                    angular.forEach($scope.games, function(game)
                    {
                        if(game.id == reg[1])
                        {
                            $scope.search = game.startTime.date;
                        }
                    });
                }
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
