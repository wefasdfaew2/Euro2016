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

var game = function (){
    return {
        templateUrl: '/bundles/eumain/js/game.html',
        restrict: 'E',
        controller: gameController,
        scope: {
            game: '=',
            participations: '=',
            bets: '='
        }
    }
};

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
        },
        delete: function(bet){
            return Bet.delete({id: bet.id}, function(){
                $scope.bet = null;
            });
        }
    };
}];

var betTable = function(){
    return {
        templateUrl: '/bundles/eumain/js/bet.html',
        restrict: 'A',
        scope: {
            bet: '='
        },
        require: 'betTable',
        controller: betController,
        link: {
            post: function postLink(scope, element, attrs, controller){
                element.find('.edit-bet').click(function(event){
                    event.preventDefault();
                    element.find('.modal').modal('show');
                });
                element.find('.delete-bet').click(function(event){
                    event.preventDefault();
                    if(confirm('Are you sure?')){
                        controller.delete(scope.bet).$promise.then(function(){
                            element.remove();
                        });
                    }
                });
                element.find('.save-bet').click(function(event){
                    event.preventDefault();
                    $(this).prop('disabled', true);
                    if(scope.bet.id){
                        controller.update(scope.bet).$promise.then(function(){
                            element.find('.modal').modal('hide');
                        }).catch(function(){
                            scope.bet = null;
                            element.find('.modal').modal('hide');
                        }).finally(function(){
                            $(this).prop('disabled', false);
                        });
                    } else {
                        controller.create(scope.bet).$promise.then(function(){
                            element.find('.modal').modal('hide');
                        }).catch(function(){
                            scope.bet = null;
                            element.find('.modal').modal('hide');
                        });
                    }
                });
            }
        }
    };
};

var gameBetTable = function(){
    return {
        templateUrl: '/bundles/eumain/js/game-bet-table.html',
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
                    element.find('.modal').modal('show');
                });
                element.find('.edit-bet').click(function(event){
                    event.preventDefault();
                    element.find('.modal').modal('show');
                });
                element.find('.delete-bet').click(function(event){
                    event.preventDefault();
                    if(confirm('Are you sure?')){
                        controllers[1].delete(scope.bet);
                    }
                });
                element.find('.save-bet').click(function(event){
                    event.preventDefault();
                    $(this).prop('disabled', true);
                    if(scope.bet.id){
                        controllers[1].update(scope.bet).$promise.then(function(){
                            element.find('.modal').modal('hide');
                        }).catch(function(){
                            scope.bet = null;
                            element.find('.modal').modal('hide');
                        }).finally(function(){
                            $(this).prop('disabled', false);
                        });
                    } else {
                        controllers[1].create(scope.bet).$promise.then(function(){
                            element.find('.modal').modal('hide');
                        }).catch(function(){
                            scope.bet = null;
                            element.find('.modal').modal('hide');
                        });
                    }
                });
            }
        }
    };
};

var directives = angular.module('directives',[]);

directives.directive('game', game);
directives.directive('betTable', betTable);
directives.directive('gameBetTable', gameBetTable);
