function complexFactory($http) {
    return {
        ComplexQuery: function (data) {
            return $http.post('http://assessment.local/php/index.php/complex-query', data);
        }
    }
};

angular
        .module('app')
        .factory('complexFactory', complexFactory);