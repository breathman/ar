var cabinet = angular.module('cabinet', []);

cabinet.controller('ServiceController', function($scope){
    $scope.name = 'Авто-планета';
});

cabinet.controller('OrderController', function($scope, $http) {
    $scope.order = {};

    $scope.order.car = ['Мазда', '6'];
    $scope.order.cost = 5800;
    $scope.order.estimate = {
        'detail': 'Дверь',
        'works':  ['Подготовка','Покраска']
    };

    $scope.getTitle = function() {
        return '#' + $scope.orders.getAttribute('key') + ' ' + $scope.order.car[0] + ' ' + $scope.order.car[1] + ' ' + $scope.order.estimate.detail;
    };

    $http.get('/app/data/K2SF.json').success(function(data) {
        $scope.orders = data;
    });


});