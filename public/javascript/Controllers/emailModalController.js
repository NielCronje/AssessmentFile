function emailModalController($scope, $modalInstance, userForm, toaster) {
    
    console.log(userForm);
    $scope.data = userForm;

    $scope.sendEmail = function() {
        toaster.pop({
            type: 'sucess',
            body: 'Email Sent.',
            showCloseButton: true,
            timeout: 2000
        });
        $scope.cancel();
    };
    
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
};

angular
        .module('app')
        .controller('emailModalController', emailModalController);