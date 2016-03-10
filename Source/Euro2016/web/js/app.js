var dependencies = [];
if(typeof controllers !== 'undefined')
{
    dependencies.push(controllers.name);
}
if(typeof directives !== 'undefined')
{
    dependencies.push(directives.name);
}
if(typeof services !== 'undefined')
{
    dependencies.push(services.name);
}
dependencies.push("ngResource");

var euro2016App = angular.module('euro2016App', dependencies).config(function($interpolateProvider) {
    $interpolateProvider.startSymbol("[[").endSymbol("]]");
}).config(function($httpProvider){
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}).config(function($httpProvider) {
    $httpProvider.interceptors.push('myHttpInterceptor');
}).factory('myHttpInterceptor', function ($q) {
    return {
        'request': function (config){
            $('#loader-ajax').show();
            return config;
        },
        'response': function (config){
            $('#loader-ajax').hide();
            return config;
        }
    };
}).filter('dateToISO', function (){
    return function (input){
        return new Date(input).toISOString();
    };
});
