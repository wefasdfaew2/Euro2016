var game = function (){
    return {
        templateUrl: '/Euro2016/Source/Euro2016/web/bundles/eumain/js/game.html',
        restrict: 'E',
        scope: {
            game: '=',
            participations: '=',
            bets: '='
        }
    }
};

var gameBetTable = function(){
    return {
        templateUrl: '/Euro2016/Source/Euro2016/web/bundles/eumain/js/game-bet-table.html',
        restrict: 'A',
        scope: {
            game: '=',
            participation: '=',
            bets: '='
        },
        controller: betController,
        link: function(scope, element, attrs, c){
            scope.bet = c.findBet(scope.participation);
        }
    };
};

var directives = angular.module('directives',[]);

directives.directive('game', game);
directives.directive('gameBetTable', gameBetTable);
