var game = function (){
    return {
        templateUrl: '/Euro2016/Source/Euro2016/web/bundles/eumain/js/game.html',
        restrict: 'E',
        controller: gameController,
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
        require: ['^game', 'gameBetTable'],
        scope: {
            game: '=',
            participation: '=',
        },
        controller: betController,
        link: {
            pre: function preLink(scope, element, attrs, controllers){
                scope.bet = controllers[0].getBet(scope.participation, scope.game);
            },
            post: function postLink(scope, element, attrs, controllers) {
                element.find('.create-bet').click(function(event){
                    event.preventDefault();
                    showModalBet(scope.game, scope.participation, scope.bet);
                });
                element.find('.edit-bet').click(function(event){
                    event.preventDefault();
                    showModalBet(scope.game, scope.participation, scope.bet);
                });
                element.find('.delete-bet').click(function(event){
                    event.preventDefault();
                    if(confirm('Are you sure?')){
                        controllers[1].delete(scope.bet);
                    }
                });
            }
        }
    };
};

var directives = angular.module('directives',[]);

directives.directive('game', game);
directives.directive('gameBetTable', gameBetTable);
