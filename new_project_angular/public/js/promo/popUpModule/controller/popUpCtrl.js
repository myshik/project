angular.module('app')
    .controller('logIn', function ($scope, $modal) {
        $scope.open = function (size) {
            var modalInstance = $modal.open({
                templateUrl: 'logIn.html',
                controller: 'ModalInstanceCtrl',
                size: size,
                resolve: {
                    items: function () {
                        return $scope.items;
                    }
                }
            });
        };
    })
    .controller('registration', function ($scope, $modal) {
        $scope.open2 = function (size) {
            var modalInstance = $modal.open({
                templateUrl: 'registration.html',
                controller: 'ModalInstanceCtrl',
                size: size,
                resolve: {
                    items: function () {
                        return $scope.items;
                    }
                }
            });
        };
    })
    .controller('ModalInstanceCtrl', function ($scope, $modalInstance, items) {});