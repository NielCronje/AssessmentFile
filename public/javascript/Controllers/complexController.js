function complexController($state, complexFactory, $scope, $rootScope) {
    $scope.filter = [];
    
    $scope.complexQuery = function(filter) {
        var data = {
            filter: filter
        };
        complexFactory.ComplexQuery(data).then(function (result) {
            $scope.list = result.data;
        });
    };
    
    $scope.manipulate = function() {
        $state.go('file-manipulate');
    };
    
    $scope.complexQuery();
};

angular
        .module('app')
        .controller('complexController', complexController);