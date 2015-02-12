/**
 *  Получить все ссылоки и юзеров
 */
angular.module('app')
    .controller('accordionCtrl', function($scope, accordionService){
        $scope.status = {
            isFirstOpen: true,
            isFirstDisabled: false
        };
        accordionService.getUser.async().then(function(data) {
            $scope.users = data;
        });
        accordionService.getLinks.async().then(function(data){
            $scope.links = data;
        })
    });