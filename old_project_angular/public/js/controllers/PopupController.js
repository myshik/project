/**
 * Created by pc3 on 13.01.15.
 */
var app = angular.module('MyApp', ['ui.bootstrap' , 'ngRoute']);
app.controller('ModalDemoCtrl', function ($scope, $modal, $log) {
    $scope.open = function (size) {
        var modalInstance = $modal.open({
            templateUrl: 'myModalContent.html',
            controller: 'ModalInstanceCtrl',
            size: size,
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });
    };
});
app.controller('ModalDemoCtrl2', function ($scope, $modal, $log) {
    $scope.open2 = function (size) {
        var modalInstance = $modal.open({
            templateUrl: 'myModalContent2.html',
            controller: 'ModalInstanceCtrl',
            size: size,
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });
    };
});
app.controller('ModalInstanceCtrl', function ($scope, $modalInstance, items) {});