function ticketsController($state, ticketFactory, $scope, $timeout, $modal, $log, $rootScope) {
    
    $scope.sortId = 'creation_date';
    $scope.offset = 0;
    $scope.to;
    $scope.category = 'Creation Date';
    $scope.statusChange = '';
    
    $scope.getTickets = function () {
        var data = {
            offset: $scope.offset,
            sortId: $scope.sortId
        };
        ticketFactory.tickets(data).then(function(data) {
            $scope.tickets = data.data;
            $timeout(function () {
                $('#pag'+($scope.offset+1)).addClass('active');
                if ($scope.offset == 0) {
                    $('#previous').addClass('disabled');
                } else {
                    $('#previous').removeClass('disabled');
                }
                if (($scope.offset + 1) == $scope.tickets.pagination.length) {
                    $('#next').addClass('disabled');
                } else {
                    $('#next').removeClass('disabled');
                }
            }, 100);
            if (($scope.offset + 1) * 10 > $scope.tickets.tickets.length) {
                $scope.to = $scope.tickets.tickets.length;
            } else {
                $scope.to = ($scope.offset + 1) * 10;
            }
        });
    };
    
    $scope.getTickets();
    
    $scope.$watch('category', function() {
        switch ($scope.category) {
            case 'Creation Date':
                $scope.sortId = 'creation_date';
                break;
            case 'Assigned Date':
                $scope.sortId = 'assigned_date';
                break;
            case 'Status':
                $scope.sortId = 'status_id';
                break;
            case 'First Name':
                $scope.sortId = 'first_name';
                break;
            case 'last_name':
                $scope.sortId = 'last_name';
                break;
        }
        $scope.getTickets();
    });
    
    $scope.saveId = function(id) {
        if ($('#statusChange'+id).val() !== $scope.statusChange && $('#statusChange'+id).val() !== '? undefined:undefined ?') {
            console.log($('#statusChange'+id).val());
            $scope.updatedTicketId = id;
            $scope.statusChange = $('#statusChange'+id).val();
            ticketFactory.ticketsUpdate({id: $scope.updatedTicketId, status: $scope.statusChange}).then(function(data){
                $scope.getTickets();
            });
        }
    };
    
    $scope.collapse = function(id) {
        if ($('#chevron'+id).hasClass('fa fa-chevron-down')) {
            $('#desc'+id).slideDown(500).css({'display': 'true'});
            $('#chevron'+id).removeClass('fa fa-chevron-down').addClass('fa fa-chevron-up');
        } else {
            $('#desc'+id).slideUp(500);
            $timeout(function() {
                $('#desc'+id).css({'display': 'none'});
            }, 500);
            $('#chevron'+id).removeClass('fa fa-chevron-up').addClass('fa fa-chevron-down');
        }
    };
    
    $scope.previous = function () {
        $('#pag'+($scope.offset+1)).removeClass('active');
        $scope.offset = $scope.offset - 1;
        $scope.getTickets();
    };
    
    $scope.next = function () {
        $('#pag'+($scope.offset+1)).removeClass('active');
        $scope.offset = $scope.offset + 1;
        $scope.getTickets();
    };
    
    $scope.selectPaginate = function (pag) {
        $('#pag'+($scope.offset+1)).removeClass('active');
        $('#pag'+($scope.offset+pag)).addClass('active');
        if (pag == 1) {
            $('#previous').addClass('disabled');
        } else {
            $('#previous').removeClass('disabled');
        }
        if (pag == $scope.tickets.pagination.length) {
            $('#next').addClass('disabled');
        } else {
            $('#next').removeClass('disabled');
        }
        $scope.offset = pag - 1;
        $scope.getTickets();
    };
    
    $scope.email = function (name, title) {
        $scope.userForm = {
            title: title,
            name: name
        };
        var modalInstance = $modal.open({
            templateUrl: 'views/modal.html',
            controller: 'emailModalController',
            scope: $scope,
            resolve: {
                userForm: function () {
                    return $scope.userForm;
                }
            }
        });

        modalInstance.result.then(function (selectedItem) {
            $scope.selected = selectedItem;
        }, function () {
            $log.info('Modal dismissed at: ' + new Date());
        });
    }
    
    $scope.logOut = function() {
        $state.go('login');
    };
};

angular
        .module('app')
        .controller('ticketsController', ticketsController);