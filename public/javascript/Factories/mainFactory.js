function mainFactory($http) {
    return {
        Login: function (data) {
            return $http.post('http://assessment.local/php/index.php/login', data);
        }
    }
};

angular
        .module('app')
        .factory('mainFactory', mainFactory);