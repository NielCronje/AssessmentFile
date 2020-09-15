function ticketFactory($http) {
    return {
        tickets: function (data) {
            return $http.post('http://assessment.local/php/index.php/tickets', data);
        },
        ticketsUpdate: function (data) {
            return $http.post('http://assessment.local/php/index.php/ticket/update', data);
        },
        logTicket: function (data) {
            return $http.post('http://assessment.local/php/index.php/ticket/log', data);
        }
    };
};

angular
        .module('app')
        .factory('ticketFactory', ticketFactory)