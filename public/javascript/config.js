function config($stateProvider, $urlRouterProvider, $ocLazyLoadProvider, $locationProvider) {


    $urlRouterProvider.otherwise("/login");
    $locationProvider.html5Mode(true);
    $ocLazyLoadProvider.config({
        debug: false
    });
    $stateProvider.state('login', {
        url: "/login",
        templateUrl: "views/login.html",
        controller: "mainController",
        data: {pageTitle: 'Login'},
        resolve: {}
    });
    
    $stateProvider.state('logTicket', {
        url: "/logTicket",
        templateUrl: "views/logTicket.html",
        controller: "logTicketController",
        data: {pageTitle: 'Log Ticket'},
        resolve: {
            loadPlugin: function ($ocLazyLoad) {
                return $ocLazyLoad.load([
                    {
                        name: 'app',
                        files: [
                            'javascript/Controllers/logTicketController.js',
                            'javascript/Factories/ticketFactory.js'
                        ]
                    },
                    {
                        name: 'datePicker',
                        files: [
                            'lib/jquery-ui/jquery-ui.min.js',
                            'lib/jquery-ui/jquery-ui.min.css'
                        ]
                    }
                ]);
            }
        }
    });
    
    $stateProvider.state('tickets', {
        url: "/tickets",
        templateUrl: "views/tickets.html",
        controller: "ticketsController",
        data: {pageTitle: 'Tickets'},
        resolve: {
            loadPlugin: function ($ocLazyLoad) {
                return $ocLazyLoad.load([
                    {
                        name: 'app',
                        files: [
                            'javascript/Controllers/ticketsController.js',
                            'javascript/Controllers/emailModalController.js',
                            'javascript/Factories/ticketFactory.js'
                        ]
                    },
                ]);
            }
        }
    });
    
    $stateProvider.state('complex-query', {
        url: "/complex-query",
        templateUrl: "views/complex-query.html",
        controller: "complexController",
        data: {pageTitle: 'Complex Query'},
        resolve: {
            loadPlugin: function ($ocLazyLoad) {
                return $ocLazyLoad.load([
                    {
                        name: 'app',
                        files: [
                            'javascript/Controllers/complexController.js',
                            'javascript/Factories/complexFactory.js'
                        ]
                    }
                ]);
            }
        }
    });
    
    $stateProvider.state('file-manipulate', {
        url: "/file-manipulate",
        templateUrl: "views/file-manipulate.html",
        controller: "manipulateController",
        data: {pageTitle: 'Complex Query'},
        resolve: {
            loadPlugin: function ($ocLazyLoad) {
                return $ocLazyLoad.load([
                    {
                        name: 'app',
                        files: [
                            'javascript/Controllers/manipulateController.js',
                            'javascript/Factories/manipulateFactory.js'
                        ]
                    }
                ]);
            }
        }
    });
};


angular
        .module('app')
        .config(config)
        .run(function ($rootScope, $state) {
            $rootScope.$state = $state;
        });