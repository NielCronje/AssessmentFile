function mainController($state, mainFactory, $scope, $rootScope) {
    $scope.login = function() {
        $scope.details = {
            'username': $scope.username,
            'password': $scope.password
        };
        mainFactory.Login($scope.details).then(function (result) {
            $rootScope.user = result.data;
            if (result.data.message === undefined) {
                if ($rootScope.user.user_type_id == 1) {
                    $state.go('logTicket');
                } else {
                    $state.go('tickets');
                }
            }
        });
    };
    
    $scope.complexQuery = function() {
        $state.go('complex-query');
    };
};

angular
        .module('app')
        .controller('mainController', mainController);