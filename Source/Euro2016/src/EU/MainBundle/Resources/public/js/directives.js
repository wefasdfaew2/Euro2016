var game = function (){
    return {
        templateUrl: '/Euro2016/Source/Euro2016/web/bundles/eumain/js/game.html',
        restrict: 'E',
        scope: {
            game: '='
        }
    }
};

var directives = angular.module('directives',[]);

directives.directive('game', game);
