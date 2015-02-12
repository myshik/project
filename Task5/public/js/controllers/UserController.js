/**
 * Created by pc3 on 13.01.15.
 */
var app = angular.module('MyApp');
app.controller('AccordionDemoCtrl', ['$scope','$http', function($scope,$http) {
    $http.get('/getuser').success(function(data) {
        $scope.users = data;
    }).error(function(data) {});
    $http.get('/getlinks').success(function(data) {
        $scope.links = data;
    }).error(function(data) {});
}]);