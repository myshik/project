angular.module('MyApp', ['ui.bootstrap'])
    .controller('AccordionDemoCtrl', function ($scope) {
    $scope.status = {
        isFirstOpen: true,
        isFirstDisabled: false
    };
});