function logTicketController($state, ticketFactory, $scope, toaster, $timeout, $rootScope) {
    $("#datepicker").datepicker();
    
    $scope.submit = function() {
        if ($scope.title == undefined || $scope.title == '') {
            toaster.pop({
                type: 'error',
                body: 'Please enter a title for the ticket.',
                showCloseButton: true,
                timeout: 3000
            });
            $('#title').focus();
        } else if ($scope.category == undefined || $scope.category == '') {
            toaster.pop({
                type: 'error',
                body: 'Please select a category for the ticket.',
                showCloseButton: true,
                timeout: 3000
            });
            $('#category').focus();
        } else if ($("#datepicker").val() == undefined || $("#datepicker").val() == '') {
            toaster.pop({
                type: 'error',
                body: 'Please select a date for the ticket.',
                showCloseButton: true,
                timeout: 3000
            });
            $('#datepicker').focus();
        } else if ($scope.desc == undefined || $scope.desc == '') {
            toaster.pop({
                type: 'error',
                body: 'Please enter a description for the ticket.',
                showCloseButton: true,
                timeout: 3000
            });
            $('#description').focus();
        } else {
            var dateObject = $("#datepicker").datepicker("getDate");
            var dateString = $.datepicker.formatDate("yy-dd-mm", dateObject);
            console.log(dateString);
            var data = {
                user_id: $rootScope.user.id,
                title: $scope.title,
                category: $scope.category,
                date: dateString,
                description: $scope.desc
            };
            ticketFactory.logTicket(data).then(function () {
                $('.logTicketForm').slideUp(500);
                toaster.pop({
                    type: 'success',
                    body: 'Ticket Logged.',
                    showCloseButton: true,
                    timeout: 2000
                });
                $timeout(function() {
                    $('.ticketSuccess').css({'visibility': 'visible'});
                }, 500);
            });
        }
    };
    
    $scope.createTicket = function() {
        $("#datepicker").val('');
        $scope.desc = '';
        $scope.title = '';
        $scope.category = '';
        $('.logTicketForm').slideDown(500);
        $('.ticketSuccess').css({'visibility': 'hidden'});
    };
    
    $scope.logOut = function() {
        $state.go('login');
    };
};

angular
        .module('app')
        .controller('logTicketController', logTicketController);


